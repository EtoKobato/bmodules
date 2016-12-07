
function Music2() {
	// Properties:
	this.audio.preload	= 'none'	// Song preload.
	this.audio.volume	= 0.25		// Song volume level.
	
	
	this.ctrls.progress
		this.ctrls.progress.min = 0;
		this.ctrls.progress.max = 1000;
		this.ctrls.progress.step = 1;
		this.ctrls.progress.defaultValue = 0;
	this.ctrls.time_display
		this.timeRemains	= false;	// Show time in pass or remain.
	this.ctrls.volume
		this.ctrls.volume.min = 0;
		this.ctrls.volume.max = 100;
		this.ctrls.volume.step = 1;
		this.ctrls.volume.defaultValue = 25;
		this.ctrls.volume.value = 25;
		this.xVolume = this.ctrls.volume.value / 100;
	this.ctrls.mute
	this.ctrls.play
	this.ctrls.pause
	this.ctrls.stop
	this.ctrls.last
	this.ctrls.next
	this.ctrls.rewind
	this.ctrls.forward
	
	this.ctrls.auto_next
		this.autoNext		= false;	// Auto play next.
	this.ctrls.shuffle
		this.shuffle		= false;	// Random mode.
	this.ctrls.repeat_list
		this.repeat_list	= false;	// List loop.
	this.ctrls.repeat_song
		this.repeat_song	= false;	// Single loop.
	
	
	
	this.isSmooth = false;		// Ctrl smooth.
	this.timeFactor = 20;		// Using in smooth.
	
	this.ctrls = new Object();
	this.info = new Array();
	this.list = new Array();
	this.audio = new Audio();
	
	
	
	// Function:
	// this.set_track	= function () {}
	this.add_track = function (id, title, performer, album, sndSRC, imgSRC) {
		if (
			id||title||performer||album||sndSRC||imgSRC == ''
		) {
			return 500;
		}
		this.info.push({
			id			: id,
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
					playlist.splice(i, 1);
					return 0;
				}
				else {
					playlist.splice(i, 1);
					return 0;
				}
			}
		}
		return 500;
	}
	this.get_track = function (id, part) {
		var L = this.list.length;
		var i = 0;
		
		for (i; i < L; i++) {
			if (this.list[i].id == id) {
				switch (part) {
					case 'title':
						this.info[i].title;
					case 'performer':
						this.info[i].performer;
					case 'album':
						this.info[i].album;
					case 'sndSRC':
						this.info[i].sndSRC;
					case 'imgSRC':
						this.info[i].imgSRC;
					default:
						return id;
				}
			}
		}
		
		return '.ERROR.'
	}
	this.s_c_r_a = function (smoothStep) {
		var smoothStep = this.audio.volume / this.timeFactor;
		if (this.audio.volume > 0) {
			this.audio.volume = 
			t0 = setTimeout('this.s_c_r_a()', );
		}
		else {
			clearTimeout(t0);
		}
	}
	this.s_c_r_b = function () {
		if (this.audio.volume > this.volume) {
			this.audio.volume = 
			t0 = setTimeout('this.s_c_r_a()', );
		}
		else {
			clearTimeout(t0);
		}
	}
	this.s_c_g = function () {
		if (this.audio.volume > 0) {
			this.audio.volume = 
			t0 = setTimeout('this.s_c_r_a()', );
		}
		else {
			clearTimeout(t0);
		}
	}
	this.smooth_ctrl = function (type) {
		if (this.isSmooth) {
			switch (type) {
				case 'pause':
					reduce();
					t = setTimeout("mainAudio.pause()", Math.round((xtime * rangeValue) / 0.01));
				break;
				case 'play':
					mainAudio.volume = 0;
					mainAudio.play();
					gain();
				break;
				case 'adjust':
					if (mainAudio.volume > rangeValue) {			// REDUCE
						reducex();
					}
					else if (mainAudio.volume < rangeValue) {		// GAIN
						gain();
					}
					else {
						mainAudio.volume = rangeValue;
					}
				break;
			}
			t = setTimeout("range1.max = parseInt(Math.round(mainAudio.duration) * 2)", Math.round((xtime * rangeValue) / 0.01) + 1500);
		}
		else {
			switch (type) {
				case 'pause':
					mainAudio.pause();
				break;
				case 'play':
					mainAudio.play();
				break;
				case 'adjust':
					mainAudio.volume = rangeValue;
				break;
			}
		}
	}

	// this.mark			= function () {}
	// this.locate			= function () {}
	
	// this.progress		= function () {}
	// this.time_display	= function () {}
	// this.volume			= function () {}
	// this.mute			= function () {}
	// this.play			= function () {}
	// this.pause			= function () {}
	// this.stop			= function () {}
	// this.last			= function () {}
	// this.next			= function () {}
	// this.rewind			= function () {}
	// this.forward			= function () {}
		
	// this.auto_next		= function () {}
	// this.shuffle			= function () {}
	// this.repeat_list		= function () {}
	// this.repeat_song		= function () {}
}

function Xctrl(
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
	Music2.ctrls = {
		progress		: c1,
		time_display	: c2,
		volume			: c3,
		mute			: c4,
		play			: c5,
		pause			: c6,
		stop			: c7,
		last			: c8,
		next			: c9,
		rewind			: c10,
		forward			: c11,
		auto_next		: c12,
		shuffle			: c13,
		repeat_list		: c14,
		repeat_song		: c15
	}
}
