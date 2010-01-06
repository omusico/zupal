﻿package {	import flash.display.MovieClip;	import flash.events.Event;	import flash.events.MouseEvent;	import game.*;	import game.synerg.Map;	public class Synerg extends MovieClip {		function Synerg() {			trace("Synerg");			// Set stage instances			STAGE=stage;			// Start button			start_button.addEventListener(MouseEvent.MOUSE_DOWN,start_game);			start_button.x=STAGE_WIDTH/2;			start_button.y=STAGE_HEIGHT/2;			showMainMenu();			this.addEventListener(Event.ENTER_FRAME, enterFrameHandler);		}		function showMainMenu():void {			trace("showMainMenu");			addChild(start_button);		}		function hideMainMenu():void {			removeChild(start_button);		}		//---> Events		function enterFrameHandler(e:Event) {			if (new_piece) {			}		}		//--->  Game		function start_game(e:MouseEvent) {			// Game init			trace("start_game");			Synerg._game = new Game();			Synerg._game.addEventListener("GAME_OVER",resetGame);			Synerg._game.start();			addChild(Synerg._game);			hideMainMenu();		}		function resetGame(e: Event) {			removeChild(Synerg._game);			Synerg._game.removeEventListener("GAME_OVER",resetGame);			Synerg._game = null;			showMainMenu();		}		function initMap(map:Array) {			for (var i:uint = 0; i < BOARD_SIZE; i++) {				map[i]=[];				for (var j:uint = 0; j < BOARD_SIZE; j++) {					map[i][j]=false;				}			}		}		public static function place_piece() {			var piece=new_piece;			if (! piece.overlaps()) {				piece.place();			}			new_piece=null;		}		public static function new_id() {			_last_id++;			return _last_id;		}				public static function game(): Game{ return Synerg._game; }		private static var _game:Game;		var start_button = new start_button_gfx();		// Static vars		public static var STAGE;		public static var STAGE_WIDTH=600;		public static var STAGE_HEIGHT=600;		public static var BOARD_SIZE=8;		public static var BOARD_MARGIN = 8;		public static var BOARD_RES=25;		public static var BOARD_X=60;		public static var BOARD_Y=25;		public static var GRASS_SIZE=4;		public static var being_map:Map = new Map("beings");		public static var wall_map:Map = new Map('walls');		public static var building_map:Map = new Map("buildings");		public static var forest_map:Map = new Map("forest");		public static var zone_map:Map = new Map("zones");		public static var terrain_map:Map = new Map("terrain");				public static var new_piece:MovieClip;		private static var _last_id=0;				public static function clamp_i(i:uint): uint {			return Math.max(0, Math.min(BOARD_SIZE-1, i));		}		public static function clamp_j(j:uint): uint {			return Math.max(0, Math.min(BOARD_SIZE-1, j));		}				public static function on_board(i:int, j:int): Boolean {			if ((i < 0) || (j < 0) || (i >= BOARD_SIZE) || (j >= BOARD_SIZE)){				return false;			}			return true;		}	}}