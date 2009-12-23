﻿package game.synerg{	import flash.geom.Point;		public class PointVector extends Point {		public var compass:String='';		public function PointVector(px, py, pc) {			super(px, py);			compass=pc;		}				// note -- preserves compass of THIS pv.		public function addpv(point){			var new_point = clone();			new_point.x += point.x;			new_point.y += point.y;			return new_point;		}				override public function clone():Point{			var out = new PointVector(x, y, compass);					return out;		}				public static function compass_name(go_to:Point, from_pt:Point){			var pv:Point = go_to.subtract(from_pt);			var angle:Number = 0;						if (!((pv.x == 0) && ( pv.y == 0))) {				angle = Math.acos(pv.x/Point.distance(go_to, from_pt));			}						var deg =  (angle * 180 / Math.PI);						angle += Math.PI/8;			var angle_index = Math.max(0, int(angle /(Math.PI/4)));						var ca = compass_angles()[angle_index];			trace('compass_name of ' + from_pt + ' to ' + go_to + '; angle = ' + deg + ", compass = " + ca);			return ca;		}				public static function compass_angles(){			trace("compass_angles(): getting " + PointVector.COMPASS_ANGLES);			return PointVector.COMPASS_ANGLES;		}				public static var COMPASS_N = 'N';		public static var COMPASS_NE = 'NE';		public static var COMPASS_E = 'E';		public static var COMPASS_SE = 'SE';		public static var COMPASS_S = 'S';		public static var COMPASS_SW = 'SW';		public static var COMPASS_W = 'W';		public static var COMPASS_NW = 'NW';		public static var COMPASS_ANGLES: Array = [PointVector.COMPASS_E, 								 PointVector.COMPASS_NE, 								 PointVector.COMPASS_N, 								 PointVector.COMPASS_NW, 								 PointVector.COMPASS_W,								 PointVector.COMPASS_SW, 								 PointVector.COMPASS_S, 								 PointVector.COMPASS_SE];	}	}