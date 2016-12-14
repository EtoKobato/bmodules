
function Music2() {
	// Properties:
	this.audio.preload	= 'none'	// Song preload.
	this.audio.volume	= 0.25		// Song volume level.
	
	// Progress bar properties:
	this.ctrls.progress.min				= 0;
	this.ctrls.progress.max				= 1000;
	this.ctrls.progress.step			= 1;
	this.ctrls.progress.defaultValue	= 0;
	
	// Show time in pass or remain:
	this.timeRemains = false;
	
	// Volume bar properties:
	this.ctrls.volume.min			= 0;
	this.ctrls.volume.max			= 100;
	this.ctrls.volume.step			= 1;
	this.ctrls.volume.defaultValue	= 25;
	this.ctrls.volume.value			= 25;
	this.xVolume = this.ctrls.volume.value / 100;
	
	// Playback type:
	this.autoNext		= false;	// Auto play next.
	this.shuffle		= false;	// Random mode.
	this.repeatList		= false;	// List loop.
	this.audio.loop		= false;	// Single loop.
	
	// Smooth playback properties:
	this.isSmooth		= false;	// Ctrl smooth.
	this.smoothStep		= 0.01;		// Smooth step 
	this.timeLength		= 500;		// (Default 500ms)Using in smooth.
	
	// Stores:
	this.ctrls = new Object();	// Store controls.
	this.info = new Array();	// Extend info for list.
	this.list = new Array();	// Play list.
	this.justPlay = ['id0', 'id1'];
	this.audio = new Audio();
	
	// Progress refresh speed, NOT in Hz but ms.:
	this.textRefreshPeriod		= 1000;	// Time text. this.ctrls.time_display0 this.ctrls.time_display1.
	this.rangeRefreshPeriod		= 1000;	// Progress bar. this.ctrls.progress
	
	// lim random() recall times:
	this.randomTimes = 0;
	
	// Function:
	
	// 
	this.add_track = function (id, title, performer, album, sndSRC, imgSRC) {
		if (
			id||title||performer||album||sndSRC||imgSRC == ''
		) {
			return 500;
		}
		this.info.push({
			//id			: id,
			title		: title,
			performer	: performer,
			album		: album,
			sndSRC		: sndSRC,
			imgSRC		: imgSRC
		});
		this.list.push(id);
		
		return 0;
	}
	this.remove_track = function (id) {
		var L = this.list.length;
		var i = 0;
		
		for (i; i < L; i++) {
			if (this.list[i].id == id) {
				if (this.list[i].id == this.audio.id) {
					this.ctrls.next.click();
				}
				this.list.splice(i, 1);
				this.info.splice(i, 1);
			}
		}
		return 500;
	}
	this.set_track	= function (id) {
		var L = this.list.length;
		var i = 0;
		
		if (this.isSmooth && this.smoothStep > 0 && this.smoothStep <= 1) {
			setTimeout("auto_next()", this.timeLength * this.smoothStep);
		}
		else {
			auto_next();
		}
		
		for (i; i < L; i++) {
			if (this.list[i].id == id) {
				this.audio.id = id;
				
				// Extra info:				
				this.audio.title		= this.info[i].title;
				this.audio.performer	= this.info[i].performer;
				this.audio.album		= this.info[i].album;
				this.audio.sndSRC		= this.info[i].sndSRC;
				this.audio.imgSRC		= this.info[i].imgSRC;
				
				return 0;
			}
		}
		return 500;
	}
	this.get_track = function (id, part = 'title') {
		var L = this.list.length;
		var i = 0;
		
		for (i; i < L; i++) {
			if (this.list[i].id == id) {
				switch (part) {
					case 'title':
						return this.info[i].title;
					case 'performer':
						return this.info[i].performer;
					case 'album':
						return this.info[i].album;
					case 'sndSRC':
						return this.info[i].sndSRC;
					case 'imgSRC':
						return this.info[i].imgSRC;
					default:
						return id;
				}
			}
		}
		
		return 500;
	}
	this.exist_track = function (id) {
		var L = this.list.length;
		var i = 0;
		
		for (i; i < L; i++) {
			if (this.list[i].id == id) {
				return true;
			}
		}
		
		return false;
	}
	this.next_track = function () {
		var L = this.list.length;
		var i = 0;
		
		if (L <= 0) return 500;
		
		this.smooth_ctrl('pause');
		
		if (this.isSmooth && this.smoothStep > 0 && this.smoothStep <= 1) {
			if (L === 1) {
				setTimeout('this.audio.currentTime = 0', this.timeLength * this.smoothStep);
				setTimeout('this.remove_track(this.audio.id)', this.timeLength * this.smoothStep);
			}
			
			if (this.repeatList) {
				if (this.shuffle) {
					// List loop:
					// Random play:
					
					setTimeout("this.set_track(this.list[this.random()].id)", this.timeLength * this.smoothStep);
					// Done;
				}
				else {
					// List loop:
					// Liner play:
					for (i; i < L ; i++) {
						if (this.list[i].id == this.audio.id) {
							if (i + 1 == L) {
								setTimeout("this.set_track(this.list[0].id)", this.timeLength * this.smoothStep);
							}
							else {
								setTimeout("this.set_track(this.list[" + i + " + 1].id)", this.timeLength * this.smoothStep);
							}
						}
					}
					// Done;
				}
			}
			else {
				if (this.shuffle) {
					// Once play:
					// Random play:
					setTimeout("this.set_track(this.list[this.random()].id)", this.timeLength * this.smoothStep);
				}
				else {
					// Once play:
					// Liner play:
					for (i ; i < L ; i++) {
						if (this.list[i].id == this.audio.id) {
							if (i + 1 == L) {
								setTimeout("this.audio.currentTime = 0", this.timeLength * this.smoothStep);
								return;
							}
							setTimeout("this.set_track(this.list[" + i + " + 1].id)", this.timeLength * this.smoothStep);
						}
					}
				}
			}

			t = setTimeout("this.smooth_ctrl('play')", this.timeLength * this.smoothStep);
		}
		else {
			if (L === 1) {
				this.audio.currentTime = 0;
				this.remove_track(this.audio.id);
			}
			if (this.repeatList) {
				if (this.shuffle) {
					// List loop:
					// Random play:
					
					this.set_track(this.list[this.random()].id);
					// Done;
				}
				else {
					// List loop:
					// Liner play:
					for (i; i < L ; i++) {
						if (this.list[i].id == this.audio.id) {
							if (i + 1 == L) {
								this.set_track(this.list[0].id);
							}
							else {
								this.set_track(this.list[i + 1].id);
							}
						}
					}
					// Done;
				}
			}
			else {
				if (this.shuffle) {
					// Once play:
					// Random play:
					this.set_track(this.list[this.random()].id);
				}
				else {
					// Once play:
					// Liner play:
					for (i ; i < L ; i++) {
						if (this.list[i].id == this.audio.id) {
							if (i + 1 == L) {
								this.audio.currentTime = 0;
								return;
							}
							this.set_track(this.list[i + 1].id);
						}
					}
				}
			}
			this.smooth_ctrl('play');
		}
		
	}
	this.s_c_r_a = function () {
		if (this.audio.volume > 0) {
			this.audio.volume -= this.smoothStep;
			t0 = setTimeout('this.s_c_r_a()', this.timeLength * this.smoothStep);
		}
		else {
			clearTimeout(t0);
		}
	}
	this.s_c_r_b = function () {
		if (this.audio.volume > this.volume) {
			this.audio.volume -= this.smoothStep;
			t0 = setTimeout('this.s_c_r_b()', this.timeLength * this.smoothStep);
		}
		else {
			clearTimeout(t0);
		}
	}
	this.s_c_g = function () {
		if (this.audio.volume > 0) {
			this.audio.volume += this.smoothStep;
			t0 = setTimeout('this.s_c_r_b()', this.timeLength * this.smoothStep);
		}
		else {
			clearTimeout(t0);
		}
	}
	this.smooth_ctrl = function (type) {
		if (this.isSmooth && this.smoothStep > 0 && this.smoothStep <= 1) {
			switch (type) {
				case 'pause':
					s_c_r_a();
					t = setTimeout("this.audio.pause()", this.timeLength);
				break;
				case 'play':
					this.audio.volume = 0;
					this.audio.play();
					s_c_g();
				break;
				case 'adjust':
					if (this.audio.volume > this.xVolume) {			// REDUCE
						s_c_r_b();
					}
					else if (this.audio.volume < this.xVolume) {		// GAIN
						s_c_g();
					}
					else {
						this.audio.volume = this.xVolume;
					}
				break;
			}
			t = setTimeout("this.ctrls.progress.max = parseInt(Math.round(this.audio.duration) * 2)", this.timeLength);
			return 0;
		}
		switch (type) {
			case 'pause':
				this.audio.pause();
			break;
			case 'play':
				this.audio.play();
			break;
			case 'adjust':
				this.audio.volume = this.xVolume;
			break;
		}
		this.ctrls.progress.max = parseInt(Math.round(this.audio.duration) * 2);
		return 0;
	}

	// this.mark			= function () {}
	// this.locate			= function () {}
	
	// this.progress		= function () {}
	this.time_display = function () {
		clearInterval(i0);
		clearInterval(i1);
		clearInterval(i2);
		if (timeRemains) {
			i0 = setInterval("$(this.ctrls.time_display0).html(this.td_group_time(2))", this.textRefreshPeriod);
		}
		else {
			i0 = setInterval("$(this.ctrls.time_display0).html(this.td_group_time(1))", this.textRefreshPeriod);
			
		}
		i1 = setInterval("$(this.ctrls.time_display1).html(this.td_group_time(0))", this.textRefreshPeriod);
		i2 = setInterval("this.ctrls.progress.value = parseInt((this.audio.currentTime / this.audio.duration) * this.ctrls.progress.max)", this.rangeRefreshPeriod);
	}
	this.td_group_time = function (type) {
		var min = '--';
		var sec = '--';
		switch (type) {
			case 0:
			// The duration:
			min = parseInt(this.audio.duration / 60).toString();
			sec = parseInt(this.audio.duration % 60).toString();
			break;
			
			case 1:
			// The current:
			min = parseInt(this.audio.currentTime / 60).toString();
			sec = parseInt(this.audio.currentTime % 60).toString();
			break;
			
			case 2:
			// The passed:
			min = parseInt((this.audio.duration - this.audio.currentTime) / 60).toString();
			sec = parseInt((this.audio.duration - this.audio.currentTime) % 60).toString();
			break;
		}
		if (!min.length == 2) min = '0' + min;
		if (!sec.length == 2) tmp = '0' + sec;
		return min + ':' + sec;
	}
	
	// this.volume			= function () {}
	// this.mute			= function () {}
	// this.play			= function () {}
	// this.pause			= function () {}
	// this.stop			= function () {}
	// this.last			= function () {}
	// this.next			= function () {}
	// this.rewind			= function () {}
	// this.forward			= function () {}
		
	this.auto_next = function () {
		if (this.autoNext) {
			$(this.audio).on('ended', function) {
				$
			}
		}
		else {
			$(this.audio).off('ended');
		}
	}
	this.random = function () {
		var L = this.list.length;
		var i = 0;
		
		xRandom = Math.floor(Math.random() * L);
		
		for (i; i < L; i++) {
			if (
				(this.list[xRandom].id == this.audio.id && L > 1) ||
				this.justPlay[0] == this.audio.id ||
				this.justPlay[1] == this.audio.id
			) {
				this.randomTimes++;
				if (this.randomTimes > 2) {
					this.randomTimes = 0;
					return xRandom;
				}
				this.random();
			}
		}
		this.randomTimes = 0;
		return xRandom;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//  Event listener:
	this.ctrls.progress.on('change', function () {
		this.smooth_ctrl('pause');
		this.audio.currentTime = parseInt((this.ctrls.progress.value / this.ctrls.progress.max) * this.audio.duration);
	});
	$('#progressLength').on('mouseenter', function (e) {
		var e = event || window.event;
		$('<p>' + ($(e.target)[0].value * 10) +  '|' + '' + '</p>').remove().insertAfter($(this));
		
		$(this).parent().find('p').css({
			'position': 'fixed',
			'top' : scrollY + e.clientY - 15 + 'px',
			'left' : scrollX + e.clientX + 'px'
		});
		
	});
	$('#progressLength').on('mouseleave', function () {
		$(this).parent().find('p').remove();
	});
}

function set_ctrl(
	c0,
	c1,
	c2,
	c3,
	c4,
	c5,
	c6,
	c7,
	c8,
	c9,
	c10,
	c11,
	c12,
	c13,
	c14,
	c15
) {
	c0.ctrls = {
		progress		: c1,
		time_display0	: c2,
		time_display1	: c3,
		volume			: c4,
		mute			: c5,
		play			: c6,
		pause			: c7,
		stop			: c8,
		last			: c9,
		next			: c10,
		rewind			: c11,
		forward			: c12,
		auto_next		: c13,
		shuffle			: c14,
		repeat_list		: c15,
		repeat_song		: c16
	}
}
