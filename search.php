<?php
    require 'craigslist.php';

    $location_urls = explode("\n", $_POST['location_urls'] ?? NULL);
    $search_uri = $_POST['search_uri'] ?? NULL;
    // $location_urls = array_slice($location_urls,0,5);

    $cl = new Craigslist();
    $cl->location_url = $location_urls;
    $cl->search_uri = $search_uri;
    $cl->proxies = explode("\n", $_POST['proxies'] ?? NULL);
    $cl->title_deduplicate = TRUE;
    $cl->filter_title_keywords = explode("\n", $_POST['filter_title_keywords'] ?? NULL);

    // $cl->debug = TRUE;
    $results = [];
    if (!empty($_POST['search'])) {
        foreach (explode(',', $_POST['search'] ?? NULL) as $search) {
            $results = array_merge($cl->search($search),$results);
        }
    }
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<body class="m-3">

    <div class="card-header">
        <button class="btn btn-link" data-toggle="collapse" data-target="#form" aria-expanded="true" aria-controls="collapseOne">
          Search Form
        </button>
    </div>
    <div class="panel-body collapse" id="form">
        <form class="form table table-bordered table-padded mb-3 p-2" method="POST">
            <div class="form-group">
                <label>Search</label>
                <input name="search" type="text" class="form-control" value="<?= $_POST['search'] ?? 'sprinter+diesel,promaster' ?>" />
            </div>

            <div class="form-group">
                <label>Search URI</label>
                <input name="search_uri" type="text" class="form-control" value="<?= $_POST['search_uri'] ?? '/cta?min_price=8000&max_price=18000&max_auto_miles=210000' ?>" />
            </div>

            <div class="form-group">
                <label>Title Exclude Filter</label>
                <textarea name="filter_title_keywords" rows="3" class="form-control">
<?= $_POST['filter_title_keywords'] ?? 'exclude title
exclude keyword
remove if found' ?>
                </textarea>
            </div>

            <div class="form-group">
                <label>Proxies</label>
                <textarea name="proxies" rows="3" class="form-control">
<?= $_POST['proxies'] ?? '1.2.3.4:1111
your.proxy.address.2:2222' ?>
                </textarea>
            </div>

            <div class="form-group">
                <label>Proxies</label>
                <textarea name="location_urls" rows="5" class="form-control">
<?= $_POST['location_urls'] ?? 'https://abilene.craigslist.org/
https://akroncanton.craigslist.org/
https://albanyga.craigslist.org/
https://albany.craigslist.org/
https://albuquerque.craigslist.org/
https://altoona.craigslist.org/
https://amarillo.craigslist.org/
https://ames.craigslist.org/
https://anchorage.craigslist.org/
https://annapolis.craigslist.org/
https://annarbor.craigslist.org/
https://appleton.craigslist.org/
https://asheville.craigslist.org/
https://ashtabula.craigslist.org/
https://athensga.craigslist.org/
https://athensohio.craigslist.org/
https://atlanta.craigslist.org/
https://auburn.craigslist.org/
https://augusta.craigslist.org/
https://austin.craigslist.org/
https://bakersfield.craigslist.org/
https://baltimore.craigslist.org/
https://batonrouge.craigslist.org/
https://battlecreek.craigslist.org/
https://beaumont.craigslist.org/
https://bellingham.craigslist.org/
https://bemidji.craigslist.org/
https://bend.craigslist.org/
https://billings.craigslist.org/
https://binghamton.craigslist.org/
https://bham.craigslist.org/
https://bismarck.craigslist.org/
https://bloomington.craigslist.org/
https://bn.craigslist.org/
https://boise.craigslist.org/
https://boone.craigslist.org/
https://boston.craigslist.org/
https://boulder.craigslist.org/
https://bgky.craigslist.org/
https://bozeman.craigslist.org/
https://brainerd.craigslist.org/
https://brownsville.craigslist.org/
https://brunswick.craigslist.org/
https://buffalo.craigslist.org/
https://butte.craigslist.org/
https://capecod.craigslist.org/
https://catskills.craigslist.org/
https://cedarrapids.craigslist.org/
https://cenla.craigslist.org/
https://centralmich.craigslist.org/
https://cnj.craigslist.org/
https://chambana.craigslist.org/
https://charleston.craigslist.org/
https://charlestonwv.craigslist.org/
https://charlotte.craigslist.org/
https://charlottesville.craigslist.org/
https://chattanooga.craigslist.org/
https://chautauqua.craigslist.org/
https://chicago.craigslist.org/
https://chico.craigslist.org/
https://chillicothe.craigslist.org/
https://cincinnati.craigslist.org/
https://clarksville.craigslist.org/
https://cleveland.craigslist.org/
https://clovis.craigslist.org/
https://collegestation.craigslist.org/
https://cosprings.craigslist.org/
https://columbiamo.craigslist.org/
https://columbia.craigslist.org/
https://columbusga.craigslist.org/
https://columbus.craigslist.org/
https://cookeville.craigslist.org/
https://corpuschristi.craigslist.org/
https://corvallis.craigslist.org/
https://chambersburg.craigslist.org/
https://dallas.craigslist.org/
https://danville.craigslist.org/
https://daytona.craigslist.org/
https://dayton.craigslist.org/
https://decatur.craigslist.org/
https://nacogdoches.craigslist.org/
https://delaware.craigslist.org/
https://delrio.craigslist.org/
https://denver.craigslist.org/
https://desmoines.craigslist.org/
https://detroit.craigslist.org/
https://dothan.craigslist.org/
https://dubuque.craigslist.org/
https://duluth.craigslist.org/
https://eastco.craigslist.org/
https://newlondon.craigslist.org/
https://eastky.craigslist.org/
https://montana.craigslist.org/
https://eastnc.craigslist.org/
https://martinsburg.craigslist.org/
https://easternshore.craigslist.org/
https://eastidaho.craigslist.org/
https://eastoregon.craigslist.org/
https://eauclaire.craigslist.org/
https://elko.craigslist.org/
https://elmira.craigslist.org/
https://elpaso.craigslist.org/
https://erie.craigslist.org/
https://eugene.craigslist.org/
https://evansville.craigslist.org/
https://fairbanks.craigslist.org/
https://fargo.craigslist.org/
https://farmington.craigslist.org/
https://fayar.craigslist.org/
https://fayetteville.craigslist.org/
https://fingerlakes.craigslist.org/
https://flagstaff.craigslist.org/
https://flint.craigslist.org/
https://shoals.craigslist.org/
https://florencesc.craigslist.org/
https://keys.craigslist.org/
https://fortcollins.craigslist.org/
https://fortdodge.craigslist.org/
https://fortsmith.craigslist.org/
https://fortwayne.craigslist.org/
https://frederick.craigslist.org/
https://fredericksburg.craigslist.org/
https://fresno.craigslist.org/
https://fortmyers.craigslist.org/
https://gadsden.craigslist.org/
https://gainesville.craigslist.org/
https://galveston.craigslist.org/
https://glensfalls.craigslist.org/
https://goldcountry.craigslist.org/
https://grandforks.craigslist.org/
https://grandisland.craigslist.org/
https://grandrapids.craigslist.org/
https://greatfalls.craigslist.org/
https://greenbay.craigslist.org/
https://greensboro.craigslist.org/
https://greenville.craigslist.org/
https://gulfport.craigslist.org/
https://norfolk.craigslist.org/
https://hanford.craigslist.org/
https://harrisburg.craigslist.org/
https://harrisonburg.craigslist.org/
https://hartford.craigslist.org/
https://hattiesburg.craigslist.org/
https://honolulu.craigslist.org/
https://cfl.craigslist.org/
https://helena.craigslist.org/
https://hickory.craigslist.org/
https://rockies.craigslist.org/
https://hiltonhead.craigslist.org/
https://holland.craigslist.org/
https://houma.craigslist.org/
https://houston.craigslist.org/
https://hudsonvalley.craigslist.org/
https://humboldt.craigslist.org/
https://huntington.craigslist.org/
https://huntsville.craigslist.org/
https://imperial.craigslist.org/
https://indianapolis.craigslist.org/
https://inlandempire.craigslist.org/
https://iowacity.craigslist.org/
https://ithaca.craigslist.org/
https://jxn.craigslist.org/
https://jackson.craigslist.org/
https://jacksontn.craigslist.org/
https://jacksonville.craigslist.org/
https://onslow.craigslist.org/
https://janesville.craigslist.org/
https://jerseyshore.craigslist.org/
https://jonesboro.craigslist.org/
https://joplin.craigslist.org/
https://kalamazoo.craigslist.org/
https://kalispell.craigslist.org/
https://kansascity.craigslist.org/
https://kenai.craigslist.org/
https://kpr.craigslist.org/
https://racine.craigslist.org/
https://killeen.craigslist.org/
https://kirksville.craigslist.org/
https://klamath.craigslist.org/
https://knoxville.craigslist.org/
https://kokomo.craigslist.org/
https://lacrosse.craigslist.org/
https://lafayette.craigslist.org/
https://tippecanoe.craigslist.org/
https://lakecharles.craigslist.org/
https://lakeland.craigslist.org/
https://loz.craigslist.org/
https://lancaster.craigslist.org/
https://lansing.craigslist.org/
https://laredo.craigslist.org/
https://lasalle.craigslist.org/
https://lascruces.craigslist.org/
https://lasvegas.craigslist.org/
https://lawrence.craigslist.org/
https://lawton.craigslist.org/
https://allentown.craigslist.org/
https://lewiston.craigslist.org/
https://lexington.craigslist.org/
https://limaohio.craigslist.org/
https://lincoln.craigslist.org/
https://littlerock.craigslist.org/
https://logan.craigslist.org/
https://longisland.craigslist.org/
https://losangeles.craigslist.org/
https://louisville.craigslist.org/
https://lubbock.craigslist.org/
https://lynchburg.craigslist.org/
https://macon.craigslist.org/
https://madison.craigslist.org/
https://maine.craigslist.org/
https://ksu.craigslist.org/
https://mankato.craigslist.org/
https://mansfield.craigslist.org/
https://masoncity.craigslist.org/
https://mattoon.craigslist.org/
https://mcallen.craigslist.org/
https://meadville.craigslist.org/
https://medford.craigslist.org/
https://memphis.craigslist.org/
https://mendocino.craigslist.org/
https://merced.craigslist.org/
https://meridian.craigslist.org/
https://milwaukee.craigslist.org/
https://minneapolis.craigslist.org/
https://missoula.craigslist.org/
https://mobile.craigslist.org/
https://modesto.craigslist.org/
https://mohave.craigslist.org/
https://monroe.craigslist.org/
https://monroemi.craigslist.org/
https://monterey.craigslist.org/
https://montgomery.craigslist.org/
https://morgantown.craigslist.org/
https://moseslake.craigslist.org/
https://muncie.craigslist.org/
https://muskegon.craigslist.org/
https://myrtlebeach.craigslist.org/
https://nashville.craigslist.org/
https://nh.craigslist.org/
https://newhaven.craigslist.org/
https://neworleans.craigslist.org/
https://blacksburg.craigslist.org/
https://newyork.craigslist.org/
https://lakecity.craigslist.org/
https://nd.craigslist.org/
https://nesd.craigslist.org/
https://nmi.craigslist.org/
https://wheeling.craigslist.org/
https://northernwi.craigslist.org/
https://newjersey.craigslist.org/
https://northmiss.craigslist.org/
https://northplatte.craigslist.org/
https://nwct.craigslist.org/
https://nwga.craigslist.org/
https://nwks.craigslist.org/
https://enid.craigslist.org/
https://ocala.craigslist.org/
https://odessa.craigslist.org/
https://ogden.craigslist.org/
https://okaloosa.craigslist.org/
https://oklahomacity.craigslist.org/
https://olympic.craigslist.org/
https://omaha.craigslist.org/
https://oneonta.craigslist.org/
https://orangecounty.craigslist.org/
https://oregoncoast.craigslist.org/
https://orlando.craigslist.org/
https://outerbanks.craigslist.org/
https://owensboro.craigslist.org/
https://palmsprings.craigslist.org/
https://panamacity.craigslist.org/
https://parkersburg.craigslist.org/
https://pensacola.craigslist.org/
https://peoria.craigslist.org/
https://philadelphia.craigslist.org/
https://phoenix.craigslist.org/
https://csd.craigslist.org/
https://pittsburgh.craigslist.org/
https://plattsburgh.craigslist.org/
https://poconos.craigslist.org/
https://porthuron.craigslist.org/
https://portland.craigslist.org/
https://potsdam.craigslist.org/
https://prescott.craigslist.org/
https://provo.craigslist.org/
https://pueblo.craigslist.org/
https://pullman.craigslist.org/
https://quadcities.craigslist.org/
https://raleigh.craigslist.org/
https://rapidcity.craigslist.org/
https://reading.craigslist.org/
https://redding.craigslist.org/
https://reno.craigslist.org/
https://providence.craigslist.org/
https://richmondin.craigslist.org/
https://richmond.craigslist.org/
https://roanoke.craigslist.org/
https://rmn.craigslist.org/
https://rochester.craigslist.org/
https://rockford.craigslist.org/
https://roseburg.craigslist.org/
https://roswell.craigslist.org/
https://sacramento.craigslist.org/
https://saginaw.craigslist.org/
https://salem.craigslist.org/
https://salina.craigslist.org/
https://saltlakecity.craigslist.org/
https://sanangelo.craigslist.org/
https://sanantonio.craigslist.org/
https://sandiego.craigslist.org/
https://sandusky.craigslist.org/
https://slo.craigslist.org/
https://sanmarcos.craigslist.org/
https://santabarbara.craigslist.org/
https://santafe.craigslist.org/
https://santamaria.craigslist.org/
https://sarasota.craigslist.org/
https://savannah.craigslist.org/
https://scottsbluff.craigslist.org/
https://scranton.craigslist.org/
https://seattle.craigslist.org/
https://sfbay.craigslist.org/
https://sheboygan.craigslist.org/
https://showlow.craigslist.org/
https://shreveport.craigslist.org/
https://sierravista.craigslist.org/
https://siouxcity.craigslist.org/
https://siouxfalls.craigslist.org/
https://siskiyou.craigslist.org/
https://skagit.craigslist.org/
https://southbend.craigslist.org/
https://southcoast.craigslist.org/
https://sd.craigslist.org/
https://juneau.craigslist.org/
https://ottumwa.craigslist.org/
https://seks.craigslist.org/
https://semo.craigslist.org/
https://carbondale.craigslist.org/
https://smd.craigslist.org/
https://swv.craigslist.org/
https://miami.craigslist.org/
https://southjersey.craigslist.org/
https://swks.craigslist.org/
https://swmi.craigslist.org/
https://marshall.craigslist.org/
https://natchez.craigslist.org/
https://bigbend.craigslist.org/
https://swva.craigslist.org/
https://spacecoast.craigslist.org/
https://spokane.craigslist.org/
https://springfieldil.craigslist.org/
https://springfield.craigslist.org/
https://pennstate.craigslist.org/
https://statesboro.craigslist.org/
https://staugustine.craigslist.org/
https://stcloud.craigslist.org/
https://stgeorge.craigslist.org/
https://stillwater.craigslist.org/
https://stjoseph.craigslist.org/
https://stlouis.craigslist.org/
https://stockton.craigslist.org/
https://susanville.craigslist.org/
https://syracuse.craigslist.org/
https://tallahassee.craigslist.org/
https://tampa.craigslist.org/
https://terrehaute.craigslist.org/
https://texarkana.craigslist.org/
https://texoma.craigslist.org/
https://thumb.craigslist.org/
https://toledo.craigslist.org/
https://topeka.craigslist.org/
https://treasure.craigslist.org/
https://tricities.craigslist.org/
https://tucson.craigslist.org/
https://tulsa.craigslist.org/
https://tuscaloosa.craigslist.org/
https://tuscarawas.craigslist.org/
https://twinfalls.craigslist.org/
https://twintiers.craigslist.org/
https://easttexas.craigslist.org/
https://up.craigslist.org/
https://utica.craigslist.org/
https://valdosta.craigslist.org/
https://ventura.craigslist.org/
https://vermont.craigslist.org/
https://victoriatx.craigslist.org/
https://visalia.craigslist.org/
https://waco.craigslist.org/
https://washingtondc.craigslist.org/
https://waterloo.craigslist.org/
https://watertown.craigslist.org/
https://wausau.craigslist.org/
https://wenatchee.craigslist.org/
https://quincy.craigslist.org/
https://westky.craigslist.org/
https://westmd.craigslist.org/
https://westernmass.craigslist.org/
https://westslope.craigslist.org/
https://wv.craigslist.org/
https://wichitafalls.craigslist.org/
https://wichita.craigslist.org/
https://williamsport.craigslist.org/
https://wilmington.craigslist.org/
https://winchester.craigslist.org/
https://winstonsalem.craigslist.org/
https://worcester.craigslist.org/
https://wyoming.craigslist.org/
https://yakima.craigslist.org/
https://york.craigslist.org/
https://youngstown.craigslist.org/
https://yubasutter.craigslist.org/
https://yuma.craigslist.org/
https://zanesville.craigslist.org/' ?>
                </textarea>
            </div>

            <button type="submit" class="btn btn-primary">Run Search</button>
        </form>
    </div>

    <br />
    <br />

    <table class="table table-bordered mt-3" id="table-results">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Date</th>
                <th>Price</th>
                <th>Img</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $idx => $result): ?>
                <tr>
                    <td><?= $idx+1 ?></td>
                    <td><?= $result['title'] ?></td>
                    <td><?= $result['date'] ?></td>
                    <td><?= $result['price'] ?></td>
                    <td><img src="<?= $result['images'][0] ?? NULL ?>" /></td>
                    <td><a target="_blank" href="<?= $result['link'] ?>"><?= $result['link'] ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $('#table-results').DataTable();

    </script>
</body>