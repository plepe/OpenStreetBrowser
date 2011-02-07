.admin,
.bound_world1 {
  line-color: #950bed;
}
.bound_world1[zoom<4] {
  line-width: 0.25;
}
.bound_world1[zoom>=4][zoom<5] {
  line-width: 0.5;
}
.bound_world1[zoom>=5][zoom<6] {
  line-width: 0.75;
}
.bound_world1[zoom>=6][zoom<7] {
  line-width: 1;
}
.admin[admin_level=2][zoom>=7][zoom<9] {
  line-width: 1.5;
}
.admin[admin_level=2][zoom>=9][zoom<11] {
  line-width: 2.5;
}
.admin[admin_level=2][zoom>=11][zoom<14] {
  line-width: 3.5;
}
.admin[admin_level=2][zoom>=14] {
  line-width: 6;
}
.admin[admin_level=3][zoom>=7][zoom<9] {
  line-width: 1.5;
  line-dasharray: 4,1;
}
.admin[admin_level=3][zoom>=9][zoom<11] {
  line-width: 2.5;
  line-dasharray: 4,1;
}
.admin[admin_level=3][zoom>=11][zoom<14] {
  line-width: 3.5;
  line-dasharray: 4,1;
}
.admin[admin_level=3][zoom>=14] {
  line-width: 6;
  line-dasharray: 6,2;
}
.admin[admin_level=4][zoom>=7][zoom<9] {
  line-width: 0.75;
}
.admin[admin_level=4][zoom>=9][zoom<11] {
  line-width: 1.5;
}
.admin[admin_level=4][zoom>=11][zoom<14] {
  line-width: 2;
}
.admin[admin_level=4][zoom>=14] {
  line-width: 4;
}
.admin[admin_level=5][zoom>=7][zoom<9] {
  line-width: 0.75;
  line-dasharray: 4,3;
}
.admin[admin_level=5][zoom>=9][zoom<11] {
  line-width: 1.5;
  line-dasharray: 4,3;
}
.admin[admin_level=5][zoom>=11][zoom<14] {
  line-width: 2;
  line-dasharray: 4,3;
}
.admin[admin_level=5][zoom>=14] {
  line-width: 4;
  line-dasharray: 4,3;
}
.admin[admin_level=6][zoom>=9][zoom<11] {
  line-width: 1;
  line-dasharray: 2,2;
}
.admin[admin_level=6][zoom>=11][zoom<14] {
  line-width: 1.5;
  line-dasharray: 2,2;
}
.admin[admin_level=6][zoom>=14] {
  line-width: 3;
  line-dasharray: 2,2;
}
.admin[admin_level=7][zoom>=11][zoom<14],
.admin[admin_level=8][zoom>=11][zoom<14] {
  line-width: 1;
  line-dasharray: 4,3;
}
.admin[admin_level=7][zoom>=14],
.admin[admin_level=8][zoom>=14] {
  line-width: 2;
  line-dasharray: 6,4;
}
.admin[admin_level=9][zoom>=11][zoom<14],
.admin[admin_level=10][zoom>=11][zoom<14] {
  line-width: 1;
  line-dasharray: 2,4;
}
.admin[admin_level=9][zoom>=14],
.admin[admin_level=10][zoom>=14] {
  line-width: 1.5;
  line-dasharray: 4,3;
}
