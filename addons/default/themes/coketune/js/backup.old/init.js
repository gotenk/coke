(function() {
  $(document).ready(function(self) {
    var BASE_HEIGHT, BASE_WIDTH, BUTTON_IMAGE, FLY_IMAGE, FOAM_IMAGE, Foam, GRID_HORIZONTAL, GRID_VERTICAL, HERO_IMAGE, PAUSE, STAIN_IMAGE, Stains, assets, background, background_2, boxtimer, button_level, button_pause, canvas, ctx, fns, fsr, h, hero, keyDown, keys, loadedAssets, oriented, requestedAssets, scale, score_obj, spriteSheets, stage, stats, ticks, touchX, uiContainer, w, world, xck, _bgpos, _bs, _hs, _ip, _l, _mns, _mxs, _ns, _s, _s1, _s2, _s3, _t, _to;
    xck = xhrck;
    oriented = void 0;
    BUTTON_IMAGE = "assets/button.png";
    HERO_IMAGE = "assets/hero.png";
    STAIN_IMAGE = "assets/stain.png";
    FOAM_IMAGE = "assets/foam.png";
    FLY_IMAGE = "assets/fly.png";
    BASE_WIDTH = 800;
    BASE_HEIGHT = 400;
    GRID_HORIZONTAL = 8;
    GRID_VERTICAL = 8;
    PAUSE = false;
    window.Game = this;
    self = this;
    w = 800;
    h = 400;
    scale = snapValue(Math.min(w / BASE_WIDTH, h / BASE_HEIGHT), .5);
    ticks = 0;
    canvas = void 0;
    ctx = void 0;
    stage = void 0;
    background = void 0;
    background_2 = void 0;
    uiContainer = void 0;
    world = void 0;
    button_level = [];
    hero = void 0;

    /*fly = undefined
    fly1 = undefined
    fly2 = undefined
    fly3 = undefined
    fly4 = undefined
    fly5 = undefined
     */
    stats = void 0;
    assets = [];
    spriteSheets = [];
    keyDown = false;
    self.width = w;
    self.height = h;
    self.scale = scale;
    fsr = 10;
    fns = 10;
    Stains = [];
    Foam = void 0;
    score_obj = void 0;
    boxtimer = void 0;
    button_pause = void 0;
    touchX = 0;
    _t = 0;
    _l = 0;
    _to = 60;
    _s = 0;
    _s1 = 0;
    _s2 = 0;
    _s3 = 0;
    _ns = 0;
    _bs = 50;
    _mxs = 50;
    _mns = 50;
    _hs = 200;
    _bgpos = 0;
    _ip = 10;
    keys = [];
    self.getStains = function() {
      return Stains;
    };
    self.preloadResources = function() {
      self.loadImage(HERO_IMAGE);
      self.loadImage(STAIN_IMAGE);
      self.loadImage(FOAM_IMAGE);
      self.loadImage(FLY_IMAGE);
    };
    requestedAssets = 0;
    loadedAssets = 0;
    self.loadImage = function(e) {
      var img;
      img = new Image();
      img.onload = self.onLoadedAsset;
      img.src = e;
      assets[e] = img;
      ++requestedAssets;
    };
    self.onLoadedAsset = function(e) {
      ++loadedAssets;
      if (loadedAssets === requestedAssets) {
        self.initLevel();
      }
    };
    self.initLevel = function(rp) {
      return $.jCryption.authenticate(tokens, ($.extend({}, xck)).x, SITE_URL + "/rinso-advanced-foam/auth-pubkey", SITE_URL + '/rinso-advanced-foam/hand-shake', function(AESKey) {
        var encryptedString, obj;
        if (rp) {
          return false;
        }
        obj = {
          action: 'init',
          data: 'asdasda'
        };
        encryptedString = $.jCryption.encrypt(JSON.stringify(obj), ($.extend({}, xck)).x);
        return $.ajax({
          url: SITE_URL + "rinso-advanced-foam/do_act",
          dataType: "json",
          type: "POST",
          data: $.extend({
            data: encryptedString
          }, tokens),
          success: function(response) {
            rp = JSON.parse($.jCryption.decrypt(response.data, ($.extend({}, xck)).x));
            return self.initGame(rp);
          }
        });
      }, function() {
        return console.log('error auth');
      });
    };
    self.initGame = function(rp) {
      var buttonText, data, i, _graphics, _shape;
      if (stage) {
        boxtimer.removeAllChildren();
        background.removeAllChildren();
        uiContainer.removeAllChildren();
        stage.removeAllChildren();
      }
      canvas = void 0;
      ctx = void 0;
      stage = void 0;
      background = void 0;
      background_2 = void 0;
      uiContainer = void 0;
      world = void 0;
      Foam = void 0;
      score_obj = void 0;
      boxtimer = void 0;
      button_pause = void 0;
      button_level = [];
      PAUSE = false;
      stats = new Stats();
      stats.domElement.style.position = 'absolute';
      stats.domElement.style.right = '0px';
      stats.domElement.style.top = '0px';
      Math.seed = parseFloat(rp.seed) || 1;
      _bs = parseFloat(rp.speed) || 50;
      _mxs = parseFloat(rp.min) || 50;
      _mns = parseFloat(rp.max) || 50;
      _hs = parseFloat(rp.hs) || 200;
      _ip = parseFloat(rp.point) || 10;
      _to = parseFloat(rp.time) || 10;
      fsr = parseFloat(rp.fsr) || 10;
      fns = parseFloat(rp.fsr) || 10;
      _s = parseFloat(rp.cp) || 0;
      _ns = parseFloat(rp.ulp) || 0;
      _l = parseFloat(rp.lvl) || 0;
      if (getWidth() > getHeight()) {
        oriented = 'landscape';
      } else {
        oriented = 'portrait';
      }
      assets[HERO_IMAGE] = nearestNeighborScale(assets[HERO_IMAGE], scale);
      assets[STAIN_IMAGE] = nearestNeighborScale(assets[STAIN_IMAGE], scale);
      assets[FOAM_IMAGE] = nearestNeighborScale(assets[FOAM_IMAGE], scale);
      if ($('canvas').length > 0) {
        $('canvas').remove();
      }
      $('body').append('<canvas>');
      canvas = $('canvas')[0];
      if (oriented === 'landscape') {
        scale = snapValue(Math.min(w / BASE_WIDTH, h / BASE_HEIGHT), .5);
        canvas.width = w;
        canvas.height = h;
      } else {
        w = 500;
        scale = snapValue(Math.min(h / BASE_HEIGHT, w / BASE_WIDTH), .5);
        canvas.width = h;
        canvas.height = w;
      }
      stage = new Stage(canvas);
      if (oriented === 'landscape') {
        background = self.createBgGrid(GRID_HORIZONTAL, GRID_VERTICAL, 400, 400);
        background.regX = -200;
      } else {
        background = self.createBgGrid(GRID_HORIZONTAL, GRID_VERTICAL, 400, 400);
        background.regY = -80;
      }
      stage.addChild(background);
      world = new Container();
      stage.addChild(world);
      uiContainer = new Container();
      if (oriented !== 'landscape') {
        _graphics = new Graphics();
        _graphics.beginFill('rgb(22,20,240)').r(0, 0, h, w * .15);
        _shape = new Shape(_graphics);
        uiContainer.addChild(_shape);
      }
      stage.addChild(uiContainer);
      data = {
        images: [BUTTON_IMAGE],
        frames: {
          width: 60,
          height: 20,
          count: 2
        },
        animations: {
          locked: 0,
          unlocked: 1
        }
      };
      for (i in rp.lvl_all_dts) {
        buttonText = new Text(rp.lvl_all_dts[i], "14px Arial", "#dce");
        button_level[i] = new Button(data, "locked", function(e) {});
        if (oriented === 'landscape') {
          button_level[i].sprite.x = w * 0.1;
          button_level[i].sprite.y = h / 4 + i * (button_level[i].sprite.spriteSheet._frameHeight + 5);
        } else {
          button_level[i].sprite.x = 25 + i * (button_level[i].sprite.spriteSheet._frameWidth + 5);
          button_level[i].sprite.y = 3;
        }
        button_level[i].sprite.gotoAndPlay("locked");
        button_level[i].text = buttonText;
        buttonText.x = button_level[i].sprite.x + 7;
        buttonText.y = button_level[i].sprite.y;
        uiContainer.addChild(button_level[i].sprite);
        uiContainer.addChild(button_level[i].text);
        if (rp.lu >= i) {
          button_level[i].sprite.gotoAndPlay("unlocked");
        }
      }
      buttonText = new Text("Pause", "14px Arial", "#dce");
      button_pause = new Button(data, "locked", function(e) {
        Ticker.setPaused(!Ticker.getPaused());
        if (Ticker.getPaused()) {
          e.target.gotoAndPlay("unlocked");
          return button_pause.text.text = "Unpause";
        } else {
          e.target.gotoAndPlay("locked");
          return button_pause.text.text = "Pause";
        }
      });
      if (oriented === 'landscape') {
        button_pause.sprite.x = w * 0.7;
        button_pause.sprite.y = h * 0.05;
      } else {
        button_pause.sprite.x = h * 0.8;
        button_pause.sprite.y = w * 0.16;
      }
      button_pause.text = buttonText;
      button_pause.text.x = button_pause.sprite.x + 5;
      button_pause.text.y = button_pause.sprite.y;
      button_pause.sprite.gotoAndPlay("locked");
      uiContainer.addChild(button_pause.sprite);
      uiContainer.addChild(button_pause.text);
      score_obj = new Text(_s + "/" + _ns, "20px Arial", "#ff7700");
      if (oriented === 'landscape') {
        score_obj.x = w * 0.82;
        score_obj.y = h * 0.3;
      } else {
        score_obj.x = h * 0.4;
        score_obj.y = w * 0.1;
      }
      score_obj.textBaseline = "alphabetic";
      uiContainer.addChild(score_obj);
      if (!boxtimer) {
        if (oriented === 'landscape') {
          boxtimer = new BoxTimer(10, 150, 'v', '#f0f');
          boxtimer.x = w * 0.85;
          boxtimer.y = h * 0.75;
          boxtimer.rotation = 180;
        } else {
          boxtimer = new BoxTimer(10, 150, 'h', '#f0f');
          boxtimer.x = h * 0.6;
          boxtimer.y = w * 0.08;
        }
        uiContainer.addChild(boxtimer);
      }
      console.log(boxtimer);
      boxtimer.SetPercentage(0);
      hero = new Hero(assets[HERO_IMAGE]);

      /*fly = new Sprite(spriteSheets[FLY_IMAGE])
      fly.gotoAndPlay "fly"
      fly1 = new Sprite(spriteSheets[FLY_IMAGE])
      fly1.gotoAndPlay "fly"
      fly2 = new Sprite(spriteSheets[FLY_IMAGE])
      fly2.gotoAndPlay "fly"
      fly3 = new Sprite(spriteSheets[FLY_IMAGE])
      fly3.gotoAndPlay "fly"
      fly4 = new Sprite(spriteSheets[FLY_IMAGE])
      fly4.gotoAndPlay "fly"
      fly5 = new Sprite(spriteSheets[FLY_IMAGE])
      fly5.gotoAndPlay "fly"
       */
      self.reset();
      stage.enableMouseOver();
      if (Touch.isSupported()) {
        Touch.enable(stage);
        stage.addEventListener("stagemousedown", self.handleKeyDown);
        stage.addEventListener("stagemousemove", self.handleKeyDown);
      } else {
        Ticker.timingMode = Ticker.RAF;
        document.onkeydown = self.handleKeyDown;
        document.onkeyup = self.handleKeyUp;
        document.onmousedown = self.handleKeyDown;
        document.onmouseup = self.handleKeyUp;
      }
      Ticker.setFPS(30);
      Ticker.removeEventListener("tick", self.tick);
      Ticker.addEventListener("tick", self.tick);
      window.onblur = function(e) {
        keys = [];
        if (!Ticker.getPaused()) {
          Ticker.setPaused(!Ticker.getPaused());
        }
        button_pause.sprite.gotoAndPlay("unlocked");
        return button_pause.text.text = "Unpause";
      };
    };
    self.reset = function() {
      var atX, atY, c, l;
      Stains = [];
      self.lastStain = null;
      world.removeAllChildren();
      c = void 0;
      l = h / (assets[STAIN_IMAGE].height * 1.5) + 2;
      atX = 0;
      atY = h / 1.25;
      c = 1;
      if (oriented === 'landscape') {
        while (c < l) {
          atX = (c - .5) * assets[STAIN_IMAGE].width * 2 + (Math.seededRandom() * assets[STAIN_IMAGE].width - assets[STAIN_IMAGE].width / 2);
          atY = (Math.seededRandom() * (500 - assets[STAIN_IMAGE].width) + 150) * scale;
          self.addStain(atY, -atX);
          c++;
        }
        atX = assets[FOAM_IMAGE].height * scale;
        atY = (Math.seededRandom() * (500 - assets[STAIN_IMAGE].width) + 150) * scale;
        self.addFoam(atY, -atX);
        hero.x = w / 2 - (hero.image.width / 2) * scale;
        hero.y = h * .85 * scale;
        hero.reset(_hs, 150, 650);
      } else {
        while (c < l) {
          atX = (c - .5) * assets[STAIN_IMAGE].width * 2 + (Math.seededRandom() * assets[STAIN_IMAGE].width - assets[STAIN_IMAGE].width / 2);
          atY = (Math.seededRandom() * (500 - assets[STAIN_IMAGE].width)) * scale;
          self.addStain(atY, -atX);
          c++;
        }
        atX = assets[FOAM_IMAGE].height * scale;
        atY = (Math.seededRandom() * (500 - assets[STAIN_IMAGE].width)) * scale;
        self.addFoam(atY, -atX);
        hero.x = h / 2 - (hero.image.width / 2) * scale;
        hero.y = w * 0.85;
        hero.reset(_hs, 0, 400);
      }
      touchX = hero.x;
      world.addChild(hero);

      /*world.addChild fly
      world.addChild fly1
      world.addChild fly2
      world.addChild fly3
      world.addChild fly4
      world.addChild fly5
       */
    };
    self.tick = function(e) {
      var c, collision, encryptedString, l, obj, offset, p;
      stats.begin();
      _bgpos += e.delta / 1000 * _bs;
      if (oriented === 'landscape') {
        background.y = (_bgpos + 50) % (h / GRID_VERTICAL);
      } else {
        background.y = (_bgpos + 50) % (h / GRID_HORIZONTAL);
      }
      if (!Ticker.getPaused()) {
        hero.tick(e);
        if (Touch.isSupported()) {
          if (keys[0]) {
            if (hero.x > keys[0] + 5 || hero.x < keys[0] - 5) {
              if (hero.x > keys[0]) {
                hero.move(-1);
              } else if (hero.x < keys[0]) {
                hero.move(1);
              }
            } else {
              hero.move(0);
            }
          } else {
            hero.move(0);
          }
        } else {
          if (keys[37] || keys[65] || keys[39] || keys[68]) {
            if (keys[37] || keys[65]) {
              hero.move(-1);
            }
            if (keys[39] || keys[68]) {
              hero.move(1);
            }
          } else {
            hero.move(0);
          }
        }
        c = void 0;
        p = void 0;
        l = void 0;
        _t = Ticker.getTime(true) / 1000;
        if (_t > _to) {
          Ticker.setPaused(true);
          l = Stains.length;
          c = 0;
          collision = null;
          while (c < l) {
            p = Stains[c];
            p.y = -p.image.height;
            c++;
          }
          obj = {
            action: 'end_game',
            s: _s,
            l: _l
          };
          encryptedString = $.jCryption.encrypt(JSON.stringify(obj), ($.extend({}, xck)).x);
          $.ajax({
            url: SITE_URL + "rinso-advanced-foam/do_act",
            dataType: "json",
            type: "POST",
            data: $.extend({
              data: encryptedString
            }, tokens),
            success: function(response) {
              var rp;
              rp = JSON.parse($.jCryption.decrypt(response.data, ($.extend({}, xck)).x));
              if (rp.message === 'stop') {
                alert('udah buyar');
                return false;
              }
              xck = {
                x: rp.nk
              };
              Ticker.reset();
              Ticker.setPaused(true);
              self.initLevel(rp);
              self.initGame(rp);
              return Ticker.setPaused(false);
            }
          });
        } else {
          boxtimer.SetPercentage(_t / _to);
        }
        l = Stains.length;
        c = 0;
        collision = null;
        while (c < l) {
          p = Stains[c];
          p.y += e.delta / 1000 * p.velocityY;
          collision = calculateCollision(p, "y", [hero]);
          if (collision) {
            _s += _ip;
            self.moveStainToEnd(p);
          }
          if (oriented === 'landscape') {
            if (p.localToGlobal(p.image.height, 0).y > h) {
              self.moveStainToEnd(p);
            }
          } else {
            if (p.localToGlobal(p.image.height, 0).y > w) {
              self.moveStainToEnd(p);
            }
          }
          c++;
        }
        if (fns < _t) {
          Foam.y += e.delta / 1000 * p.velocityY;
          collision = calculateCollision(Foam, "y", [hero]);
          offset = false;
          if (oriented === 'landscape') {
            offset = Foam.localToGlobal(Foam.image.height, 0).y > h;
          } else {
            offset = Foam.localToGlobal(Foam.image.height, 0).y > w;
          }
          if (collision || offset) {
            if (collision) {
              hero.grow();
            }
            fns += fsr;
            if (oriented === 'landscape') {
              Foam.x = (Math.seededRandom() * (500 - Foam.image.width) + 150) * scale;
              Foam.y = -assets[FOAM_IMAGE].height * scale;
            } else {
              Foam.x = (Math.seededRandom() * (500 - Foam.image.width)) * scale;
              Foam.y = h * 0.1 - assets[FOAM_IMAGE].height * scale;
            }
          }
        }
        score_obj.text = _s + '/' + _ns;
      }
      stage.update();
      stats.end();
    };
    self.createBgGrid = function(numX, numY, _width, _height) {
      var c, gh, grid, gw, horizontalLine, hs, verticalLine, vs;
      grid = new Container();
      grid.snapToPixel = true;
      gw = _width / numX;
      gh = _height / numY;
      verticalLine = new Graphics();
      verticalLine.beginFill(Graphics.getRGB(101, 60, 176));
      verticalLine.drawRect(0, 0, gw * 0.03, gh * (numY + 2));
      vs = void 0;
      c = -1;
      while (c < numX + 2) {
        vs = new Shape(verticalLine);
        vs.snapToPixel = true;
        vs.x = c * gw;
        vs.y = -gh;
        grid.addChild(vs);
        c++;
      }
      horizontalLine = new Graphics();
      horizontalLine.beginFill(Graphics.getRGB(101, 60, 176));
      horizontalLine.drawRect(0, 0, gw * (numX + 2), gh * 0.03);
      hs = void 0;
      c = -1;
      while (c < numY + 1) {
        hs = new Shape(horizontalLine);
        hs.snapToPixel = true;
        hs.x = -1 * gw;
        hs.y = c * gh;
        grid.addChild(hs);
        c++;
      }
      return grid;
    };
    self.lastStain = null;
    self.addStain = function(x, y) {
      var Stain;
      x = Math.round(x);
      y = Math.round(y);
      Stain = new Bitmap(assets[STAIN_IMAGE]);
      Stain.x = x;
      Stain.y = y;
      Stain.velocityY = Math.seededRandom() * (_mxs - _mns) + _mns;
      Stain.snapToPixel = true;
      world.addChild(Stain);
      Stains.push(Stain);
      self.lastStain = Stain;
    };
    self.addFoam = function(x, y) {
      x = Math.round(x);
      y = Math.round(y);
      Foam = new Bitmap(assets[FOAM_IMAGE]);
      Foam.x = x;
      Foam.y = y;
      Foam.velocityY = Math.seededRandom() * (_mxs - _mns) + _mns;
      Foam.snapToPixel = true;
      world.addChild(Foam);
    };
    self.moveStainToEnd = function(Stain) {
      Stain.velocityY = Math.seededRandom() * (_mxs - _mns) + _mns;
      if (oriented === 'landscape') {
        Stain.y = self.lastStain.y - Stain.image.width * 2 - (Math.seededRandom() * Stain.image.width * 2 - Stain.image.width) * -1;
        Stain.x = (Math.seededRandom() * (500 - Stain.image.width) + 150) * scale;
      } else {
        Stain.y = self.lastStain.y - Stain.image.width * 2 - (Math.seededRandom() * Stain.image.width * 2 - Stain.image.width) * -1;
        Stain.x = (Math.seededRandom() * (500 - Stain.image.width)) * scale;
      }
      self.lastStain = Stain;
    };
    self.handleKeyDown = function(e) {
      if (Touch.isSupported()) {
        keys[0] = e.stageX;
      } else {
        keys[e.keyCode] = true;
      }
      if (!keyDown) {
        keyDown = true;
      }
    };
    self.handleKeyUp = function(e) {
      if (Touch.isSupported()) {
        keys = [];
      } else {
        delete keys[e.keyCode];
      }
    };
    self.preloadResources();
  });

}).call(this);
