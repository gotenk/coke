(function() {
  (function(window) {
    var Hero, max, min, scaled;
    Hero = function(image) {
      this.initialize(image);
    };
    Hero.prototype = new createjs.Bitmap();
    Hero.prototype.Bitmap_initialize = Hero.prototype.initialize;
    scaled = 0;
    min = 0;
    max = 100;
    Hero.prototype.initialize = function(i) {
      this.reset();
      this.Bitmap_initialize(i);
      this.name = "Hero";
      this.snapToPixel = true;
    };
    Hero.prototype.reset = function(s, _min, _max) {
      this.direction = 0;
      if (s) {
        this.s = s;
      }
      this.min = _min;
      this.max = _max;
    };
    Hero.prototype.grow = function() {
      if (scaled < 5) {
        this.scaleX += .2;
        this.scaleY += .2;
        return scaled++;
      }
    };
    Hero.prototype.tick = function(event) {
      var moveBy;
      moveBy = event.delta / 1000 * (this.s * this.direction);
      if (this.x + moveBy < this.min) {
        this.x = this.min;
      } else if (this.x + moveBy > this.max - this.image.width * this.scaleX) {
        this.x = this.max - this.image.width * this.scaleX;
      } else {
        this.x += moveBy;
      }
    };
    Hero.prototype.move = function(dir) {
      this.direction = dir;
    };
    window.Hero = Hero;
  })(window);

}).call(this);
