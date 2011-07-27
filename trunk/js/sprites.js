function intersect(circle, rect)
{
    circleDistance = { 
    		x: Math.abs(circle.x - rect.x - rect.width/2),
    		y: Math.abs(circle.y - rect.y - rect.height/2)
    };
    var r = circle.width/2;
    if (circleDistance.x > (rect.width/2 + r)) { return false; }
    if (circleDistance.y > (rect.height/2 + r)) { return false; }

    if (circleDistance.x <= (rect.width/2)) { return true; } 
    if (circleDistance.y <= (rect.height/2)) { return true; }

    cornerDistance_sq = Math.pow(circleDistance.x - rect.width/2, 2) +
                         Math.pow(circleDistance.y - rect.height/2, 2);

    return (cornerDistance_sq <= Math.pow(r, 2));
}

function Enum() {}
Enum.CollisionEdge = {
		none: 0,
		left: 1,
		top: 2,
		right: 3,
		bottom: 4,
		leftTop: 5,
		leftBottom: 6,
		rightTop: 7,
		rightBottom: 8
};
 
function collisionEdge(circle, rectangle) {
	if (!intersect(circle, rectangle))
		return Enum.CollisionEdge.none;

	var horiz = (circle.x > rectangle.x && circle.x < rectangle.right());
	var bottom = (Math.abs(circle.y - rectangle.y) > Math.abs(circle.y - rectangle.bottom()));
	if (horiz) {
		if (bottom)
			return Enum.CollisionEdge.bottom;
		else
			return Enum.CollisionEdge.top; // top
	}
	var vert = (circle.y > rectangle.y && circle.y < rectangle.bottom());
	var right = (Math.abs(circle.x - rectangle.x) > Math.abs(circle.x - rectangle.right()));
	if (vert) {
		if (right)
			return Enum.CollisionEdge.right;
		else
			return Enum.CollisionEdge.left; // top
	}
	
	if (bottom && right)
		return Enum.CollisionEdge.rightBottom;
	else if (bottom && !right)
		return Enum.CollisionEdge.leftBottom;
	else if (!bottom && right)
		return Enum.CollisionEdge.rightTop;
	else
		return Enum.CollisionEdge.leftTop;
}

function check_collision(A, B)
{
    //The sides of the rectangles
    var leftA = A.x;
    var leftB;
    var rightA = A.x + A.width;
    var rightB;
    var topA = A.y;
    var topB;
    var bottomA = A.y + A.height;
    var bottomB;
       
    //Calculate the sides of rect B
    leftB = B.x;
    rightB = B.x + B.width;
    topB = B.y;
    bottomB = B.y + B.height;
    if( bottomA <= topB ) {
        return false;
    }
    
    if( topA >= bottomB){
        return false;
    }
    
    if( rightA <= leftB ){
        return false;
    }
    
    if( leftA >= rightB){
        return false;
    }
    
    //If none of the sides from A are outside B
    return true;
}

function extend(Child, Parent) {
	var F = function(){};
	F.prototype = Parent.prototype;
	Child.prototype = new F();
	Child.prototype.constructor = Child;
	Child.uber = Parent.prototype;
}

function Sprite() {
	this.x = 0;
	this.y = 0;
	this.width = 0;
	this.height = 0;
	this.color = "#FF0000";
	this.dx = 0;
	this.dy = 0;
	var that = this;
	this.getLocation = function() {
		return {
			x: this.x,
			y: this.y
		};
	};
   
	this.getSize = function() {
		return {
			width: this.width,
			height: this.height
		};
	};
}

Sprite.prototype.draw = function(dc) {
	//document.write(dc.fillStyle);
};

Sprite.prototype.calcNextFrame = function() {
	
	var xCalc = this.x + this.dx;
	var yCalc = this.y + this.dy;
		
	return {
		x: xCalc,
		y: yCalc
	};
};

Sprite.prototype.performNext = function(xCur, yCur) {
	
	if (arguments.length > 0) {
		this.x = xCur;
		this.y = yCur;
	}
	else {
		this.x += this.dx;
		this.y += this.dy;
	}
};

Sprite.prototype.right = function() {
	return this.x + this.width;
};

Sprite.prototype.bottom = function() {
	return this.y + this.height;
};

//Ball.prototype = new Sprite;
//Ball.prototype.constructor = Ball;
extend(Ball, Sprite);

function Ball() {
	Sprite.call(this);
	this.dx = 1;
	this.dy = 2;
}

Ball.prototype.draw = function(dc){
	
	Sprite.prototype.draw.call(this, dc);
	dc.fillStyle = this.color;
	dc.beginPath();
	dc.arc(this.x, this.y, this.width/2, 0, Math.PI*2, true);
	dc.stroke();
	dc.fill();
	dc.closePath();
};

Ball.prototype.right = function() {
	return this.x + this.width/2;
};

Ball.prototype.bottom = function() {
	return this.y + this.width/2;
};

//Brick.prototype = new Sprite;
//Brick.prototype.constructor = Brick;
extend(Brick, Sprite);

function Brick() {
	this.hitLeft = 1;
	this.weight = 10;
	this.fixed = false;
	
}
Brick.prototype.draw = function(dc){
	Sprite.prototype.draw.call(this, dc);
	dc.strokeStyle='#000000';
	dc.strokeRect(this.x,this.y,this.width,this.height);
	dc.fillStyle = this.color;
	dc.fillRect(this.x + dc.lineWidth,this.y + dc.lineWidth,this.width - 2*dc.lineWidth, this.height - 2*dc.lineWidth);
};

//Bat.prototype = new Sprite;
//Bat.prototype.constructor = Bat;
extend(Bat, Sprite);

function Bat() {
	Sprite.call(this);
	this.color = "#A00000";
	this.cornerRadius = 0;
	this.dx = 4;
}

Bat.prototype.draw = function(dc){
	Sprite.prototype.draw.call(this, dc);
	dc.strokeStyle = this.color;
	dc.lineJoin = "round";
	dc.lineWidth = this.cornerRadius;
	dc.strokeRect(this.x+(this.cornerRadius/2), this.y+(this.cornerRadius/2), this.width-this.cornerRadius,
			this.height-this.cornerRadius);
};

extend(Layout, Sprite);
function Layout(width, height) {
	this.sprites = [];
	
	this.x = 0;
	this.y = 0;
	this.width = width;
	this.height = height;
	this.id = 1;
	this.name = "Level " + 1;
}

Layout.prototype.addSprite = function(sprite) {
	this.sprites[this.sprites.length] = sprite;
};

Layout.prototype.draw = function(dc) {
	this.fillStyle = "#736F6E";
	dc.strokeRect(this.x, this.y, this.width, this.height);
	$.each(this.sprites, function(index, sprite) {
		sprite.draw(dc);
	});
};

Layout.prototype.performNext = function(xCur, yCur) {

	$.each(this.sprites, function(index, sprite) {
		if (arguments.length > 0)
			sprite.performNext(xCur, yCur);
		else
			sprite.performNext();
	});
};

Layout.prototype.calcNextFrame = function() {
	var nextFrames = [];
	$.each(this.sprites, function(index, sprite) {
		nextFrames[index] = sprite.calcNextFrame();
	});
	return nextFrames;
};

function createSprite(x, y, width, height, color, weight, score) {
	var sprite = new Brick();
	sprite.x = x;
	sprite.y = y;
	sprite.width = width;
	sprite.height = height;
	sprite.color = color;
	sprite.weight = weight;
	sprite.hitLeft = score;
	if (score == -1){
		sprite.fixed = true;
	}
		
	return sprite;
}

var Status = {
		exit: 0,
		gameOver: 1,
		level: 2
};

function Animation(canvas) {
	this.balls = [];
	this.bats = [];

	this.context = canvas.getContext("2d");
	this.layout = new Layout(this.context.canvas.width, this.context.canvas.height);
	this.init();
}

Animation.prototype.init = function() {
	
	if (this.animationRunning)
		this.stop();

	this.layout.sprites.length = 0;
	this.context.lineWidth = 1;
	this.refreshRate = 20;
	this.timerId = 0;

	this.animationRunning = false;
	this.ballCount = 5;
	this.score = 0;
	this.level = 1;
	
	this.updateScore();
	this.updateBallCount();
	
	this.draw();
};

Animation.prototype.adjustLayout = function(width, height, id, name) {
	this.layout.id = id;
	this.layout.name = name;
	this.updateLevel();
	return {
		dx: this.context.canvas.width/width,
		dy: this.context.canvas.height/height
	};
};

Animation.prototype.createDefaults = function() {
	this.bats.length = 0;
	this.balls.length = 0;
	
	var width = 60;
	var height = 8;
	
	var x = (this.layout.width - width)/2;
	var y = (this.layout.height - 8) - 2;
	var bat = this.createBat(x, y, width, height, "#00FF00");
	this.bats[this.bats.length] = bat;
	
	x = bat.x + bat.width/2;
	var radius = 6;
	y = bat.y - radius;
	this.balls[this.balls.length] = this.createBall(x, y, radius, "#FF0000");
};

Animation.prototype.draw = function() {

	this.context.save();
	this.context.clearRect(0, 0, this.layout.width, this.layout.height);
	
	// draw layout
	this.layout.draw(this.context);
	
	// draw balls
	var self = this;
	$.each(this.balls, function(index, ball) {
		ball.draw(self.context);
	});
	
	// draw bats
	$.each(this.bats, function(index, bat) {
		bat.draw(self.context);
	});
	
	this.context.restore();
};

Animation.prototype.calcNextFrame = function() {
};

Animation.prototype.loadLevel = function(level) {
	var self = this;
	self.layout.sprites.length = 0;
	this.createDefaults();
	
	var url = "index.php?action=layout&layout=" + (level - 1);

	$.ajax({
		type: 'GET',
		url: url, 
		dataType: 'json',
		data: {},
		async: false,
		success: function(json) {
	
			if ($.isEmptyObject(json)) {
				self.gameOver();
				return;
			}
			else
			{
				self.showDialog(Status.level);
			}
			
			$.each(json, function(key, value) {
				
				var ad = self.adjustLayout(value.Width, value.Height, Number(value.Id), value.Name);
				url = "index.php?action=layout&blocks=" + Number(value.Id);
				$.ajax({
					type: 'GET',
					url: url, 
					dataType: 'json',
					data: {},
					async: false,
					success: function(jsBlocks) {
						$.each(jsBlocks, function(key, value) {
							var block = value.values;
							if (block.Backcolor != null && block.Backcolor.length > 7)
							{// remove alpha value
								block.Backcolor = block.Backcolor.substr(0, 1) + block.Backcolor.substr(3, block.Backcolor.length); 
							}
							animation.layout.addSprite(createSprite(parseInt(Number(block.X)*ad.dx), parseInt(Number(block.Y)*ad.dy), parseInt(Number(block.Width)*ad.dx), parseInt(Number(block.Height)*ad.dy), block.Backcolor, parseInt(Number(block.Weight)), parseInt(Number(block.Score))));
						});
					}
				});
			});
		}
	});
};

Animation.prototype.showDialog = function(status)
{
	var self = this;
	$dialog = $('<div></div>')
	.dialog({
		autoOpen: false,
		title: 'Parabox',
		hide: 'clip',
		modal: true,
		resizable: false,
		position: [160,100],
		height: 120 });

	switch(status)
	{
	case Status.level:
		{
			$dialog.html('Level ' + self.level);
			$dialog.dialog("option", {
				buttons: {
					"OK": function() {
						$( this ).dialog( "close" );
					}
				}
			});
		}
		break;
	case Status.exit:
		{
			var animationRunning = self.animationRunning; 
			self.stop();
			$dialog.html("Do you want to quit?");
			$dialog.dialog("option", {
				buttons: {
					"Post Score": function() {

						$( this ).dialog( "close" );
						transferScore(self.level, self.score, function(){
							self.init();	
						});
					},
					"Cancel": function() {
						$( this ).dialog( "close" );
						if (animationRunning)
							self.start();
						self.draw();
					}
				}
			});
			
		}
		break;
	case Status.gameOver:
		{
			self.stop();
			$dialog.html("Game Over!");
			$dialog.dialog("option", {
				buttons: {
					"Post Score": function() {
						$( this ).dialog( "close" );
						transferScore(self.level, self.score, function(){
							self.init();	
						});
						
					},
				}
			});
			
		}
	}
	$dialog.dialog('open');
};

Animation.prototype.playCollision = function(){
	var canPlayType = $('audio')[0].canPlayType("audio/wav");

	if(canPlayType.match(/maybe|probably/i)) {
		$('audio').attr('src', 'audio/pingpong.wav');
		$('audio').get(0).play();
	}
}

Animation.prototype.performNextFrame = function(){

	var self = this;
	for(index = 0; index < this.balls.length; ++index) {
		var ball = this.balls[index];
		var pos = ball.calcNextFrame();
		for(spriteIndex = 0; spriteIndex < self.layout.sprites.length; ++spriteIndex) {
			var sprite = self.layout.sprites[spriteIndex];
			var nextBallDim = {x: pos.x, y: pos.y, width: ball.width};
			if (intersect(nextBallDim, sprite)) {
				var edge = collisionEdge(nextBallDim, sprite);
				switch(edge) {
				case Enum.CollisionEdge.left:
					ball.dx = -Math.abs(ball.dx);
					break;
				case Enum.CollisionEdge.top:
					ball.dy = -Math.abs(ball.dy);
					break;
				case Enum.CollisionEdge.right:
					ball.dx = Math.abs(ball.dx);
					break;
				case Enum.CollisionEdge.bottom:
					ball.dy = Math.abs(ball.dy);
					break;
				case Enum.CollisionEdge.leftTop:
					ball.dx = -Math.abs(ball.dx);
					ball.dy = -Math.abs(ball.dy);
					break;
				case Enum.CollisionEdge.rightTop:
					ball.dx = Math.abs(ball.dx);
					ball.dy = -Math.abs(ball.dy);
					break;
				case Enum.CollisionEdge.leftBottom:
					ball.dx = -Math.abs(ball.dx);
					ball.dy = Math.abs(ball.dy);
					break;
				case Enum.CollisionEdge.rightBottom:
					ball.dx = Math.abs(ball.dx);
					ball.dy = Math.abs(ball.dy);
					break;
				}
				if (edge != Enum.CollisionEdge.none) {
					
					self.playCollision();
					
					pos.x = ball.x;
					pos.y = ball.y;

					if (sprite.weight > 0){
						self.score += sprite.weight;
					}
					self.updateScore();
					if (!sprite.fixed){
						--sprite.hitLeft;
						if (sprite.hitLeft <= 0) {
							self.layout.sprites.splice(spriteIndex, 1);
						}
					}
					break;
				}
			}
		}
		
		$.each(self.bats, function(batIndex, bat){
			if (intersect({x: pos.x, y: pos.y, width: ball.width}, bat)) {
				ball.dy = -Math.abs(ball.dy);
				pos.y = bat.y - ball.width/2;
				self.playCollision();
			}
		});
		
		if (pos.x < ball.width/2){
			ball.dx = Math.abs(ball.dx);
			pos.x = ball.width/2;
		} else if (pos.y < ball.width/2){
			ball.dy = Math.abs(ball.dy);
			pos.y = ball.width/2;
		}
		
		if (pos.x + ball.width/2 > self.layout.width){
			ball.dx = -Math.abs(ball.dx);
			pos.x = self.layout.width - ball.width/2;
			self.playCollision();
		} else if (pos.y + ball.width/2 >= self.layout.height){
			ball = null;
			self.balls.splice(index, 1);
		}
		if (ball != null)
			ball.performNext(pos.x, pos.y);
	}
	
	if (self.balls.length == 0) {
		this.stop();
		this.bats[0].x = this.layout.width / 2 - 20;
		this.ballCount = this.ballCount - 1;
		this.updateBallCount();
		
		if (this.ballCount > 0){
			this.createDefaults();
		}
		else {
			this.gameOver();
			return;
		}
	}
	
	var spriteCount = 0;
	$.each(this.layout.sprites, function(spriteIndex, sprite) {
		if (!sprite.fixed) {
			++spriteCount;
		}
	});
	
	if (spriteCount == 0) {
		this.stop();
		this.balls.length = 0;
		this.bats.length = 0;
		this.level = this.level + 1;
		this.loadLevel(this.level);
	}
	
	this.draw();
};

Animation.prototype.gameOver = function() {
	this.stop();
	this.balls.length = 0;
	this.draw();
	this.showDialog(Status.gameOver);
};

Animation.prototype.updateScore = function() {
	$("#score").html(this.score);
};

Animation.prototype.updateBallCount = function() {
	$("#balls").html(this.ballCount);
};

Animation.prototype.updateLevel = function() {
	$("#level").html(this.level);
};

Animation.prototype.start = function() {
	// check if animation possible

	if (this.animationRunning)
		this.stop();

	if (this.ballCount <= 0)
		return;
	var self = this;
	this.timerId = setInterval(function() { self.performNextFrame(); }, this.refreshRate);
	this.animationRunning = true;
};

Animation.prototype.stop = function() {
	
	if (this.animationRunning && this.timerId != 0)
		clearInterval(this.timerId);
	this.timerId = 0;
	this.animationRunning = false;
};

Animation.prototype.pixelsOfSpeed = function() {
	
};

Animation.prototype.createBall = function(x, y, radius, color) {
	var ball = new Ball();
	ball.x = x;
	ball.y = y;
	ball.width = radius*2;
	ball.height = radius*2;
	ball.color = color;
	return ball;
};

Animation.prototype.createBat = function(x, y, width, height, color) {
	var bat = new Bat();
	bat.x = x;
	bat.y = y;
	bat.width = width;
	bat.height = height;
	bat.color = color;
	//bat.cornerRadius = 15;
	return bat;
};

Animation.prototype.moveRight = function() {
	var self = this;
	$.each(this.bats, function(index, bat) {
		
	    var maxRight = self.layout.width - bat.width - 2;
	    var nextFrame =	bat.x + bat.dx;
	    if (nextFrame > maxRight) {
	    	nextFrame = maxRight;
	    }
		bat.performNext(nextFrame, bat.y);
	
		if (!self.animationRunning && index < self.balls.length)
		{
			x = bat.x + bat.width/2;
			var radius = 6;
			y = bat.y - radius;
			
			self.balls[index].performNext(bat.x + bat.width/2,
				bat.y - self.balls[index].height/2);
		}
	});
	
	if (!this.animationRunning)
	{
		this.draw();
	}
};

Animation.prototype.moveLeft = function() {
	var self = this;
	$.each(this.bats, function(index, bat) {
		var nextFrame =	bat.x - bat.dx;
	    if (nextFrame < 2) {
	    	nextFrame = 2;
	    }
	    bat.performNext(nextFrame, bat.y);
	    
		if (!self.animationRunning && index < self.balls.length) {
			self.balls[index].performNext(bat.x + bat.width/2,
					bat.y - self.balls[index].height/2);
		}
	});
	
	if (!this.animationRunning)
		this.draw();
};
