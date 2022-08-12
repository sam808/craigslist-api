<?php

    class Craigslist
    {
        public $location_url = 'http://honolulu.craigslist.org/search/';
        public $search_uri = '?';
        public $max_requests = 10;
        public $curl_options = [];
        public $results = [];
        public $debug = FALSE;
        public $title_filter_keywords = [];
        public $proxies = [];
        public $title_deduplicate = FALSE;

        private $titles = [];

        public function parse_results($html)
        {
            $items = [];

            $dom = new \DOMDocument();
            $dom->strictErrorChecking = false;
            @$dom->loadHTML($html); // don't throw HTML parsing errors
            $xpath = new \DomXPath($dom);

            $list = $xpath->query(".//li[@class='result-row']");

            if ($list->length == 0) {
                return [];
            }

            foreach ($list as $idx => $item) {
                $date = $xpath->query(".//time[@class='result-date']", $item)->item(0)->textContent;
                $link = $xpath->query(".//a[contains(@class, 'result-image')]/@href", $item)->item(0)->textContent;
                $title = $xpath->query(".//h3[@class='result-heading']/a", $item)->item(0)->textContent;
                $price = $xpath->query(".//span[@class='result-price']", $item)->item(0)->textContent;
                
                $location = NULL;
                if ($xpath->query(".//span[@class='result-hood']", $item)->length > 0) {
                    $location = $xpath->query(".//span[@class='result-hood']", $item)->item(0)->textContent;
                }

                $images = [];
                if ($xpath->query(".//a[contains(@class, 'result-image')]/@data-ids", $item)->length > 0) {
                    $img_refs = $xpath->query(".//a[contains(@class, 'result-image')]/@data-ids", $item)->item(0)->textContent;
                    $img_refs = explode(',', $img_refs);


                    foreach ($img_refs as $img) {
                        $images[] = 'https://images.craigslist.org/' . substr($img, 2) . '_300x300.jpg';
                    }
                }

                if ($this->title_deduplicate && in_array(strtolower($title), $this->titles)) {
                    if ($this->debug) {
                        echo '[' . date('Y-m-d H:i:s') . '] removing due to title duplicate ' . $title . PHP_EOL;
                    };
                    
                    continue;
                }

                if (!empty($this->filter_title_keywords)) {
                    foreach ($this->filter_title_keywords as $keyword) {
                        if (strpos(strtolower($title), trim(strtolower($keyword))) !== FALSE) {
                            if ($this->debug) {
                                echo '[' . date('Y-m-d H:i:s') . '] removing due to title keyword match ' . $title . PHP_EOL;
                            };
                            
                            continue 2;
                        }
                    }
                }

                $this->titles[] = strtolower($title);

                $items[] = [
                    'date'          => date('Y-m-d', strtotime($date)),
                    'link'          => $link,
                    'title'         => $title,
                    'price'         => $price,
                    'location'      => $location,
                    'images'        => $images,
                ];

            }

            return $items;
        }

        public function append_results($html)
        {
            $this->results = array_merge(
                $this->parse_results($html),
                $this->results
            );
        }

        public function render_results()
        {
            echo json_encode($this->results);
        }

        public function search($query)
        {
            $this->results = [];
            $location_urls = !is_array($this->location_url) ? [$this->location_url] : $this->location_url;
            
            // multi handle
            $mh = curl_multi_init();
            $sessions = [];

            foreach ($location_urls as $idx => $location_url) {

                $url = trim($location_url) . 'search/' . trim($this->search_uri, '/') . '&query=' . $query;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt_array($ch, $this->curl_options);
                curl_setopt($ch, CURLOPT_URL, $url);
           
                if ($this->debug) {
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                }

                if (!empty($this->proxies)) {
                    $proxy = $this->proxies[rand(0, sizeof($this->proxies) - 1)];
                    if ($this->debug) {
                        echo '[' . date('Y-m-d H:i:s') . '] using proxy ' . $proxy . PHP_EOL;
                    }
                    
                    curl_setopt($ch, CURLOPT_PROXY, $proxy);
                }

                if ($this->debug) {
                    echo '[' . date('Y-m-d H:i:s') . '] Requesting URL ' . $url . PHP_EOL;
                }

                $sessions[$idx] = $ch;

                curl_multi_add_handle($mh, $sessions[$idx]);
            }
 
            $active = NULL;
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($mh) == -1) {
                    usleep(1);
                }

                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }

            // get content and remove handles
            foreach($sessions as $key => $ch) {
                $content = curl_multi_getcontent($ch);

                $this->append_results($content);

                curl_multi_remove_handle($mh, $ch);
            }

            return $this->results;
        }
    }
