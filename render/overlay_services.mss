.amenity_services name {
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-dy: 14;
  text-halo-fill: #ffffff;
  text-halo-radius: 1;
  point-allow-overlap: true;
}
.amenity_services desc {
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-dy: 24;
  text-halo-fill: #ffffff;
  text-halo-radius: 1;
}

.amenity_services[type=communication][sub_type=t1][zoom>=15] { /* t1=post_box */
  point-file: url('img/post_box.png');
}
.amenity_services[type=communication][sub_type=t2][zoom>=13] { /* t2=post_office */
  point-file: url('img/post_office.png');
}
.amenity_services[type=communication][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #ffe985;
  text-size: 10;
}
.amenity_services[type=communication][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #ffe985;
  text-size: 8;
}

.amenity_services[type=economic][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #ffe8a2;
  text-size: 10;
  text-dy: 0;
}
.amenity_services[type=economic][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #ffe8a2;
  text-size: 8;
  text-dy: 10;
}
.amenity_services[type=economic][sub_type=t1][zoom>=15] { /* t1=atm */
  point-file: url('img/atm.png');
}
.amenity_services[type=economic][sub_type=t1][zoom>=16] name {
  text-dy: 12;
}

.amenity_services[type=services][sub_type=t1][zoom>=14][zoom<15] { /* t1=recycling */
  point-file: url('img/recycling_small.png');
}
.amenity_services[type=services][sub_type=t1][zoom>=15] { /* t1=recycling */
  point-file: url('img/recycling.png');
}
.amenity_services[type=services][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #ccbd78;
  text-size: 10;
}
.amenity_services[type=services][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #ccbd78;
  text-size: 8;
}


.amenity_services[type=emergency][sub_type=t1][zoom>=11] name { /* t1=hospital */
  point-file: url('img/hospital.png');
}
.amenity_services[type=emergency][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #df9ea5;
  text-size: 10;
}
.amenity_services[type=emergency][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #df9ea5;
  text-size: 8;
}
.amenity_services[type=health][sub_type=t1][zoom>=13] name { /* t1=pharmacy */
  point-file: url('img/pharmacy.png');
}
.amenity_services[type=health][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #abffcb;
  text-size: 10;
}
.amenity_services[type=health][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #abffcb;
  text-size: 8;
}

.amenity_services[type=education][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #e39ccf;
  text-size: 10;
  text-dy: 0;
}
.amenity_services[type=education][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #e39ccf;
  text-size: 8;
  text-dy: 10;
}

.amenity_services[type=tourism][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #8f9ed8;
  text-size: 10;
}
.amenity_services[type=tourism][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #8f9ed8;
  text-size: 8;
}
.amenity_services[type=tourism][sub_type=t1][zoom>=13] name {
  point-file: url('img/hotel.png');
}
.amenity_services[type=tourism][sub_type=t2][zoom>=13] name {
  point-file: url('img/camp_site.png');
}
.amenity_services[type=tourism][sub_type=t3][zoom>=15] name {
  point-file: url('img/tourist_info.png');
}
.amenity_services[type=tourism][sub_type=t0][zoom>=16] name {
  text-dy: 0;
}
.amenity_services[type=tourism][sub_type=t0][zoom>=16] desc {
  text-dy: 10;
}

