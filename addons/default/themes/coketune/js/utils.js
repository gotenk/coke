(function() {
  var calculateCollision, calculateIntersection, getBounds, getColorForPercentage, getHeight, getParameterByName, getWidth, nearestNeighborScale, percentColors, snapValue;

  (function(window) {
    var Button;
    Button = function(d, l, c) {
      var helper, sprite, spriteSheet;
      spriteSheet = new SpriteSheet(d);
      sprite = new Sprite(spriteSheet);
      helper = new ButtonHelper(sprite, null, null, null, true);
      sprite.addEventListener("click", c);
      this.name = "Button";
      return {
        sprite: sprite,
        helper: helper
      };
    };
    window.Button = Button;
  })(window);

  (function(window) {
    var BoxTimer, g, max, type, width, _graphics;
    BoxTimer = function(w, m, t, c) {
      this.initialize(w, m, t, c);
      return this;
    };
    _graphics = void 0;
    g = void 0;
    type = void 0;
    width = 0;
    max = 0;
    BoxTimer.prototype = new Container();
    BoxTimer.prototype.initialize = function(w, m, t, c) {
      var s1, s2;
      this.type = t;
      this.max = m;
      this.width = w;
      g = new Graphics();
      g.setStrokeStyle(1, "square").beginStroke(c);
      if (t === 'v') {
        g.r(0, 0, w, m);
      } else {
        g.r(0, 0, m, w);
      }
      s1 = new Shape(g);
      this.addChild(s1);
      _graphics = new Graphics();
      _graphics.beginFill(c);
      if (t === 'v') {
        _graphics.r(0, 0, w, 0);
      } else {
        _graphics.r(0, 0, m, 0);
      }
      s2 = new Shape(_graphics);
      return this.addChild(s2);
    };
    BoxTimer.prototype.SetPercentage = function(p) {
      if (this.type === 'v') {
        if (p > 1) {
          p = 1;
        }
        return _graphics.clear().beginFill(getColorForPercentage(p)).r(0, 0, this.width, p * this.max);
      } else {
        return _graphics.clear().beginFill(getColorForPercentage(p)).r(0, 0, p * this.max, this.width);
      }
    };
    BoxTimer.prototype.Destroy = function() {
      return this.removeAllChildren();
    };
    window.BoxTimer = BoxTimer;
  })(window);

  getParameterByName = function(name) {
    var regex, regexS, results;
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    regexS = "[\\?&]" + name + "=([^&#]*)";
    regex = new RegExp(regexS);
    results = regex.exec(window.location.search);
    if (results == null) {
      return "";
    } else {
      return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
  };

  calculateIntersection = function(rect1, rect2, x, y) {
    var dx, dy, r1, r2;
    x = x || 0;
    y = y || 0;
    dx = void 0;
    dy = void 0;
    r1 = {};
    r2 = {};
    r1.cx = rect1.x + x + (r1.hw = rect1.width / 2);
    r1.cy = rect1.y + y + (r1.hh = rect1.height / 2);
    r2.cx = rect2.x + (r2.hw = rect2.width / 2);
    r2.cy = rect2.y + (r2.hh = rect2.height / 2);
    dx = Math.abs(r1.cx - r2.cx) - (r1.hw + r2.hw);
    dy = Math.abs(r1.cy - r2.cy) - (r1.hh + r2.hh);
    if (dx < 0 && dy < 0) {
      return {
        width: -dx,
        height: -dy
      };
    } else {
      return null;
    }
  };

  calculateCollision = function(obj, direction, collideables, moveBy) {
    var bounds, cbounds, cc, collision, measure, oppositeDirection, oppositeMeasure, sign, wentThroughBackwards, wentThroughForwards, withinOppositeBounds;
    moveBy = moveBy || {
      x: 0,
      y: 0
    };
    if (direction !== "x" && direction !== "y") {
      direction = "x";
    }
    measure = (direction === "x" ? "width" : "height");
    oppositeDirection = (direction === "x" ? "y" : "x");
    oppositeMeasure = (direction === "x" ? "height" : "width");
    bounds = getBounds(obj, true);
    cbounds = void 0;
    collision = null;
    cc = 0;
    while (!collision && cc < collideables.length) {
      cbounds = getBounds(collideables[cc], true);
      if (collideables[cc].isVisible) {
        collision = calculateIntersection(bounds, cbounds, moveBy.x, moveBy.y);
      }
      if (!collision && collideables[cc].isVisible) {
        wentThroughForwards = bounds[direction] < cbounds[direction] && bounds[direction] + moveBy[direction] > cbounds[direction];
        wentThroughBackwards = bounds[direction] > cbounds[direction] && bounds[direction] + moveBy[direction] < cbounds[direction];
        withinOppositeBounds = !(bounds[oppositeDirection] + bounds[oppositeMeasure] < cbounds[oppositeDirection]) && !(bounds[oppositeDirection] > cbounds[oppositeDirection] + cbounds[oppositeMeasure]);
        if ((wentThroughForwards || wentThroughBackwards) && withinOppositeBounds) {
          moveBy[direction] = cbounds[direction] - bounds[direction];
        } else {
          cc++;
        }
      }
    }
    if (collision) {
      sign = Math.abs(moveBy[direction]) / moveBy[direction];
      moveBy[direction] -= collision[measure] * sign;
    }
    return collision;
  };

  getBounds = function(obj, rounded) {
    var bounds, c, cbounds, children, gp, imgr, l;
    bounds = {
      x: Infinity,
      y: Infinity,
      width: 0,
      height: 0
    };
    if (obj instanceof Container) {
      children = object.children;
      l = children.length;
      cbounds = void 0;
      c = void 0;
      c = 0;
      while (c < l) {
        cbounds = getBounds(children[c]);
        if (cbounds.x < bounds.x) {
          bounds.x = cbounds.x;
        }
        if (cbounds.y < bounds.y) {
          bounds.y = cbounds.y;
        }
        if (cbounds.width > bounds.width) {
          bounds.width = cbounds.width;
        }
        if (cbounds.height > bounds.height) {
          bounds.height = cbounds.height;
        }
        c++;
      }
    } else {
      gp = void 0;
      imgr = void 0;
      if (obj instanceof Bitmap) {
        gp = obj.localToGlobal(0, 0);
        imgr = {
          width: obj.image.width,
          height: obj.image.height
        };
      } else if (obj instanceof BitmapAnimation) {
        gp = obj.localToGlobal(0, 0);
        imgr = obj.spriteSheet._frames[obj.currentFrame];
      } else {
        return bounds;
      }
      bounds.width = imgr.width * Math.abs(obj.scaleX);
      if (obj.scaleX >= 0) {
        bounds.x = gp.x;
      } else {
        bounds.x = gp.x - bounds.width;
      }
      bounds.height = imgr.height * Math.abs(obj.scaleY);
      if (obj.scaleX >= 0) {
        bounds.y = gp.y;
      } else {
        bounds.y = gp.y - bounds.height;
      }
    }
    if (rounded) {
      bounds.x = (bounds.x + (bounds.x > 0 ? .5 : -.5)) | 0;
      bounds.y = (bounds.y + (bounds.y > 0 ? .5 : -.5)) | 0;
      bounds.width = (bounds.width + (bounds.width > 0 ? .5 : -.5)) | 0;
      bounds.height = (bounds.height + (bounds.height > 0 ? .5 : -.5)) | 0;
    }
    return bounds;
  };

  nearestNeighborScale = function(img, scale) {
    var a, b, dst_canvas, dst_ctx, g, offset, pixelSize, r, src_canvas, src_ctx, src_data, x, y;
    scale = snapValue(scale, .5);
    if (scale <= 0) {
      scale = 0.5;
    }
    pixelSize = (scale + 0.99) | 0;
    src_canvas = document.createElement("canvas");
    src_canvas.width = img.width;
    src_canvas.height = img.height;
    src_ctx = src_canvas.getContext("2d");
    src_ctx.drawImage(img, 0, 0);
    src_data = src_ctx.getImageData(0, 0, img.width, img.height).data;
    dst_canvas = document.createElement("canvas");
    dst_canvas.width = (img.width * scale + 1) | 0;
    dst_canvas.height = (img.height * scale + 1) | 0;
    dst_ctx = dst_canvas.getContext("2d");
    offset = 0;
    y = 0;
    while (y < img.height) {
      x = 0;
      while (x < img.width) {
        r = src_data[offset++];
        g = src_data[offset++];
        b = src_data[offset++];
        a = src_data[offset++] / 255;
        dst_ctx.fillStyle = "rgba(" + r + "," + g + "," + b + "," + a + ")";
        dst_ctx.fillRect(x * scale, y * scale, pixelSize, pixelSize);
        ++x;
      }
      ++y;
    }
    return dst_canvas;
  };

  snapValue = function(value, snap) {
    var roundedSnap;
    roundedSnap = (value / snap + (value > 0 ? .5 : -.5)) | 0;
    return roundedSnap * snap;
  };

  getWidth = function() {
    if (typeof window.innerWidth === "number") {
      return window.innerWidth;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
      return document.documentElement.clientWidth;
    } else {
      if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        return document.body.clientWidth;
      }
    }
  };

  getHeight = function() {
    if (typeof window.innerWidth === "number") {
      return window.innerHeight;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
      return document.documentElement.clientHeight;
    } else {
      if (document.body && (document.body.clientHeight || document.body.clientHeight)) {
        return document.body.clientHeight;
      }
    }
  };

  Math.seededRandom = function(max, min) {
    var rnd;
    max = max || 1;
    min = min || 0;
    Math.seed = (Math.seed * 9301 + 49297) % 233280;
    rnd = Math.seed / 233280.0;
    return min + rnd * (max - min);
  };

  percentColors = [
    {
      pct: 0.0,
      color: {
        r: 0,
        g: 0xff,
        b: 0
      }
    }, {
      pct: 0.8,
      color: {
        r: 0xff,
        g: 0xff,
        b: 0
      }
    }, {
      pct: 1.0,
      color: {
        r: 0xff,
        g: 0,
        b: 0
      }
    }
  ];

  getColorForPercentage = function(pct) {
    var color, i, lower, pctLower, pctUpper, range, rangePct, upper;
    i = 1;
    while (i < percentColors.length - 1) {
      if (pct < percentColors[i].pct) {
        break;
      }
      i++;
    }
    lower = percentColors[i - 1];
    upper = percentColors[i];
    range = upper.pct - lower.pct;
    rangePct = (pct - lower.pct) / range;
    pctLower = 1 - rangePct;
    pctUpper = rangePct;
    color = {
      r: Math.floor(lower.color.r * pctLower + upper.color.r * pctUpper),
      g: Math.floor(lower.color.g * pctLower + upper.color.g * pctUpper),
      b: Math.floor(lower.color.b * pctLower + upper.color.b * pctUpper)
    };
    return "rgb(" + [color.r, color.g, color.b].join(",") + ")";
  };

}).call(this);
