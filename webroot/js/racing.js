/**
 * Racing Game
 */
 
/** 
 * Shim layer, polyfill, for requestAnimationFrame with setTimeout fallback.
 * http://paulirish.com/2011/requestanimationframe-for-smart-animating/
 */ 
window.requestAnimFrame = (function(){
  return  window.requestAnimationFrame       || 
          window.webkitRequestAnimationFrame || 
          window.mozRequestAnimationFrame    || 
          window.oRequestAnimationFrame      || 
          window.msRequestAnimationFrame     || 
          function( callback ){
            window.setTimeout(callback, 1000 / 60);
          };
})();
 
 
/**
 * Shim layer, polyfill, for cancelAnimationFrame with setTimeout fallback.
 */
window.cancelRequestAnimFrame = (function(){
  return  window.cancelRequestAnimationFrame || 
          window.webkitCancelRequestAnimationFrame || 
          window.mozCancelRequestAnimationFrame    || 
          window.oCancelRequestAnimationFrame      || 
          window.msCancelRequestAnimationFrame     || 
          window.clearTimeout;
})();

/**
 * Trace the keys pressed
 * http://nokarma.org/2011/02/27/javascript-game-development-keyboard-input/index.html
 */
window.Key = {
  pressed: {},

  LEFT:   37,
  UP:     38,
  RIGHT:  39,
  DOWN:   40,
  SPACE:  32,
  A:      65,
  S:      83,
  D:      68,
  W:      87,
  R:      82,
  H:      72,
  
  isDown: function(keyCode, keyCode1) {
    return this.pressed[keyCode] || this.pressed[keyCode1];
  },
  
  onKeydown: function(event) {
    this.pressed[event.keyCode] = true;
  },
  
  onKeyup: function(event) {
    delete this.pressed[event.keyCode];
  }
};

window.addEventListener('keyup',   function(event) { Key.onKeyup(event); },   false);
window.addEventListener('keydown', function(event) { Key.onKeydown(event); }, false);

/**
 * The car object
 */
function Car(x, y) {
    this.img = new Image();
	this.img.onload = function(){
	  // execute drawImage statements here
	};
	this.img.src = 'img/racing/s90.png';
	
    // Sound effects
    this.enginefast = new Audio('sound/engine.mp3');
    this.engineslow = new Audio('sound/engine.mp3');
    this.tires = new Audio('sound/brake.mp3');
    this.horn = new Audio('sound/horn.mp3');
    this.turn = new Audio('sound/turn.mp3');
    this.crash = new Audio('sound/crash.mp3');
    this.revving = new Audio('sound/revving.mp3');
    
    // Collision
	this.collisions = {
		top: new CollisionPoint(this, 0),
		right: new CollisionPoint(this, 90, 10),
		bottom: new CollisionPoint(this, 180),
		left: new CollisionPoint(this, 270, 10)
	};
}

Car.prototype = {
	x: 740,
	y: 320,
	acceleration: 1.02,
	rotation: 340,
	speed: 0,
	speedDecay: 0.99,
	maxSpeed: 3.9,
	rotationStep: 2,
	backSpeed: 1.01,
	lapstarttime: 0.00,
	checkpoint1: false,
	checkpoint2: false,
	lap: null,
	lastlap: null,
	diff: null,
	bestlap: null,
	numlaps: 0,
	
	isMoving: function (speed) {
		return !(this.speed > -0.4 && this.speed < 0.4);
	},
	getCenter: function(){
		return {
			x: this.x,
			y: this.y
		};
	},
	accelerate: function(){
		if (this.speed < this.maxSpeed){
			if (this.speed < 0){
				this.speed *= this.speedDecay;
			} else if (this.speed === 0){
				this.speed = 0.4;
			} else {
				this.speed *= this.acceleration;
				if (this.speed > 2){
				    //this.enginefast.play();
				} else {
				    //this.engineslow.play();
				}
			}
		}
	},
	decelerate: function(min){
		min = min || 0;
		if (Math.abs(this.speed) < this.maxSpeed){
			if (this.speed > 0){
				this.speed *= this.speedDecay;
				this.speed = this.speed < min ? min : this.speed;
			} else if (this.speed === 0){
				this.speed = -0.4;
			} else {
				this.speed *= this.backSpeed;
				this.speed = this.speed > min ? min : this.speed;
			}
		}
	},
	brake: function(min){
		min = min || 0;
		if (Math.abs(this.speed) < this.maxSpeed){
			if (this.speed > 0){
				this.speed *= (this.speedDecay - 0.01);
				this.speed = this.speed < min ? min : this.speed;
				this.tires.play();
			} else if (this.speed === 0){
				this.speed = 0;
			}
		}
	},
	steerLeft: function(){
		if (this.isMoving()){
			this.rotation -= this.rotationStep / 1.5 + (this.speed/this.maxSpeed);
			this.turn.play();
		}
	},
	steerRight: function(){
		if (this.isMoving()){
			this.rotation += this.rotationStep / 1.5 + (this.speed/this.maxSpeed);
			this.turn.play();
		}
	},
	honk: function(){
	    this.horn.play();
	}
};

/**
 * A hit map class for dynamically
 * checking whether an x/y coordinate
 * with an appropriate hit image
 *
 * @param {Image} img The hit map image
 */
function HitMap(img){
	var self = this;
	this.img = img;

	// only do the drawing once the
	// image has downloaded
	if (img.complete){
		this.draw();
	} else {
		img.onload = function(){
			self.draw();
		};
	}
}
HitMap.prototype = {
	draw: function(){
		// first create the canvas
		this.canvas = document.createElement('canvas');
		this.canvas.width = this.img.width;
		this.canvas.height = this.img.height;
		this.ct = this.canvas.getContext('2d');
		// draw the image on it
		this.ct.drawImage(this.img, 0, 0);
	},
	isHit: function(x, y){
        if (this.ct){
            // get the pixel RGBA values
            var pixel = this.ct.getImageData(x, y, 1, 1);
            if (pixel.data[0] === 0){
                // we consider a hit if green
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
	},
	crashes: function(x, y){
        if (this.ct){
            // get the pixel RGBA values
            var pixel = this.ct.getImageData(x, y, 1, 1);
            if (pixel.data[1] === 0){
                // we consider a hit if red
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
	}
};

function CollisionPoint (car, rotation, distance) {
	this.car = car;
	this.rotation = rotation;
	this.distance = distance || this.distance;
}
CollisionPoint.prototype = {
	car: null,
	rotation: 0,
	distance: 20,
	getXY: function(){
		return rotatePoint(
					this.car.getCenter(),
					this.car.rotation + this.rotation,
					this.distance
				);
	},
    isHit: function(hitMap){
        var xy = this.getXY();
        return hitMap.isHit(xy.x, xy.y);
    },
    crashes: function(hitMap){
        var xy = this.getXY();
        return hitMap.crashes(xy.x, xy.y);
    }
};

function CollisionRadius () {
}
CollisionRadius.prototype = {
	x: 0,
	y: 0,
	radius: 10,
	check: function(coords){
	}
};


/**
*   Support functions
*
*/

var TO_RADIANS = Math.PI/180;

function speedXY (rotation, speed) {
	return {
		x: Math.sin(rotation * TO_RADIANS) * speed,
		y: Math.cos(rotation * TO_RADIANS) * speed * -1,
	};
}

function drawRotatedImage(image, x, y, angle, ct) {
	ct.save();
	ct.translate(x, y);
	ct.rotate(angle * TO_RADIANS);
	ct.drawImage(image, -(image.width/2), -(image.height/2));
	ct.restore();
}

function rotatePoint (coords, angle, distance) {
	return {
		x: Math.sin(angle * TO_RADIANS) * distance + coords.x,
		y: Math.cos(angle * TO_RADIANS) * distance * -1 + coords.y,
	};
}

function distance (from, to) {
	var a = from.x > to.x ? from.x - to.x : to.x - from.x,
		b = from.y > to.y ? from.y - to.y : to.y - from.y
		;
	return Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2))
}

function passedStartline (x,y) {
    if((657 < x && x < 716) && (165 < y && y < 191)) {
        return true;
    } else {
        return false;
    }
}

function passedCheckpoint1 (x,y) {
    if((62 < x && x < 115) && (354 < y && y < 390)) {
        return true;
    } else {
        return false;
    }
}

function passedCheckpoint2 (x,y) {
    if((780 < x && x < 810) && (500 < y && y < 581)) {
        return true;
    } else {
        return false;
    }
}

function secondsToTimeString(s) {
    if(!s) {
        return '--.--.--';
    }
    if(s >= 0) {
        var minutes = Math.floor(s / 60);
        var seconds = s % 60;
        return minutes + "." + seconds.toFixed(4);
    } else {
        s = Math.abs(s)
        var minutes = Math.floor(s / 60);
        var seconds = s % 60;
        return '-' + minutes + "." + seconds.toFixed(4);
    }
}

function stringStartsWith (string, prefix) {
    return string.slice(0, prefix.length) == prefix;
}
 
/**
*   The game module
*
*/

window.Racing = (function(){
  var canvas, ct, car, trackHit, hit, lastGameTick, width, height, lap, lastlap, diff, bestlap, savelap, numlaps;

  var init = function(canvas) {
    
    canvas = document.getElementById(canvas);
    ct = canvas.getContext('2d');
    width = canvas.width;
    height = canvas.height;
    car = new Car();
    trackHit = new Image();
    lap = document.getElementById('lap');
    lastlap = document.getElementById('lastlap');
    diff = document.getElementById('diff');
    bestlap = document.getElementById('bestlap');
    numlaps = document.getElementById('numlaps');
    savelap = document.getElementById('savelap');
    savenumlaps = document.getElementById('savenumlaps');
    
    // If there is a saved best laptime, add it to the bestlap variable
    if(savelap) {
        car.bestlap = savelap.value;
        bestlap.innerHTML = 'Best: ' + secondsToTimeString(car.bestlap);
    } else {
        bestlap.innerHTML = 'Best: --.--.--';
    }
    // If there is a saved number of laps, add it to the numlaps variable
    if(savenumlaps) {
        car.numlaps = savenumlaps.value;
        numlaps.innerHTML = 'Laps: ' + car.numlaps;
    } else {
        car.numlaps = 0;
        numlaps.innerHTML = 'Laps: 0';
    }
    
    trackHit.src = "img/racing/track-border.png";
    hit = new HitMap(trackHit);
    
    console.log('Init the game');
  };
  
  var x=10,y=10;
  var update = function(car) {
		if (!car.isMoving()){
			car.speed = 0;
		} else {
			car.speed *= car.speedDecay;
		}
		if (Key.isDown(Key.UP, Key.W)) { car.accelerate(); }
		if (Key.isDown(Key.R, Key.DOWN)) { car.decelerate(); }
		if (Key.isDown(Key.LEFT, Key.A)) { car.steerLeft(); }
		if (Key.isDown(Key.RIGHT, Key.D)) {car.steerRight(); }
		if (Key.isDown(Key.S)) {car.brake(); }
		if (Key.isDown(Key.H)) {car.honk(); }

		var speedAxis = speedXY(car.rotation, car.speed);
		car.x += speedAxis.x;
		car.y += speedAxis.y;
		
		// Drive on grass
		if (car.collisions.left.isHit(hit)){
			car.decelerate(1);
			car.revving.play();
		}
		if (car.collisions.right.isHit(hit)){
			car.decelerate(1);
			car.revving.play();
		}
		if (car.collisions.top.isHit(hit)){
			car.decelerate(1);
			car.revving.play();
		}
		if (car.collisions.bottom.isHit(hit)){
			car.decelerate(1);
			car.revving.play();
		}
		// Crash
		if (car.collisions.left.crashes(hit)){
		    car.steerRight();
			car.speed = 0;
			car.crash.play();
		}
		if (car.collisions.right.crashes(hit)){
		    car.steerLeft();
			car.speed = 0;
			car.crash.play();
		}
		if (car.collisions.top.crashes(hit)){
			car.speed = 0;
			car.crash.play();
		}
		if (car.collisions.bottom.crashes(hit)){
			car.speed = 0;
			car.crash.play();
		}
		
		// Check if checkpoint 1 was passed
		if(passedCheckpoint1(car.x,car.y)) {
		    console.log("You passed checkpoint 1!");
		    car.checkpoint1 = true;
		}
		// Check if checkpoint 2 was passed
		if(passedCheckpoint2(car.x,car.y)) {
		    console.log("You passed checkpoint 2!");
		    car.checkpoint2 = true;
		}
		// Check if finishline was passed and update laptime
		if(passedStartline(car.x, car.y)) {
		    if(car.checkpoint1 && car.checkpoint2) {
		        // If the lap is valid
		        var stoptime = Math.round(1000 * new Date()) / 1000000; 
		        car.lastlap = stoptime - car.lapstart;
		        var lastTimer = secondsToTimeString(car.lastlap);
		        console.log("You passed the finishline at " + lastTimer + "!");
		        lastlap.innerHTML = 'Last: ' + lastTimer;
		        car.numlaps++;
		        if(savenumlaps) {
		            savenumlaps.value = car.numlaps;
		        }
		        numlaps.innerHTML = 'Laps: ' + car.numlaps;
		        car.checkpoint1 = false;
		        car.checkpoint2 = false;
		        car.lapstart = Math.round(1000 * new Date()) / 1000000;
		        // Check the time difference between last and best lap
		        if(car.bestlap) {
		            car.diff = car.lastlap - car.bestlap;
		            diffTimer = secondsToTimeString(car.diff);
		            diff.innerHTML = diffTimer;
		            if(stringStartsWith(diffTimer, '-')) {
		                // Make green
		                diff.className = 'green';
		            } else {
		                // Make red
		                diff.className = 'red';
		            }
		        }
		        // Check if it was the fastest lap so far
		        if((car.bestlap) && (car.lastlap < car.bestlap)){
		            car.bestlap = car.lastlap;
		            bestlap.innerHTML = 'Best: ' + lastTimer;
		            if(savelap) {
		                savelap.value = car.bestlap;
		            }
		        } else if(!car.bestlap){
		            car.bestlap = car.lastlap;
		            bestlap.innerHTML = 'Best: ' + lastTimer;
		            if(savelap) {
		                savelap.value = car.bestlap;
		            }
		        }
		        // If not valid lap, start anew
		    } else {
		        car.lapstart = Math.round(1000 * new Date()) / 1000000;
		        console.log("You started a new lap");
		    }
		}
		// Update the current laptime
		var currentLap = Math.round(1000 * new Date()) / 1000000;
		car.lap = currentLap - car.lapstart;
		var currentTimer = secondsToTimeString(car.lap);
		if (currentTimer){
		    lap.innerHTML = 'Lap: ' + currentTimer;
		} else {
		    lap.innerHTML = 'Lap: --.--.--';
		}
  };
  
  var render = function(car) {
    ct.clearRect(0,0,width,height);
	drawRotatedImage(car.img, car.x, car.y, car.rotation, ct);
  };

  var gameLoop = function() {
      
      var now = Date.now();
      td = (now - (lastGameTick || now)) / 1000;
      lastGameTick = Date.now();
      requestAnimFrame(gameLoop);
      update(car);
      render(car);
  };

  return {
    'init': init,
    'gameLoop': gameLoop
  }
})();
