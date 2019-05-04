<html>
<title>DataBase Project</title>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
	integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
	integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
	crossorigin=""></script>

<body>
	<h1>Trottinettes</h1>
	Best website ever!

<h1>Map</h1>

 <div id="map" style="width: 800px; height: 440px; border: 1px solid #AAA;"></div>

<script>

	var map = L.map('map').setView([50.51, 4.21],7);
	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
 	subdomains: ['a', 'b', 'c']
}).addTo(map)

var marker = L.marker([50.51, 4.21]).addTo(map);
marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();

</script>

</body>
</html>
