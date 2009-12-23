﻿package game{	import flash.geom.Point;	import flash.display.MovieClip;	import fl.motion.Color;	import flash.geom.ColorTransform;	public class Pog extends MovieClip {		// Constants:		// Public Properties:		public static var POGS:Vector.<Pog>=Vector.<Pog>([]);		// Private Properties:		private var _point:Point_ij;		// UI Elements:		// Initialization:		public function Pog(p: Point_ij, ct = false) {			configUI(p, ct);			POGS.push(this);		}		// Public Methods:		// Protected Methods:		// Private Methods:		protected function configUI(p, ct):void {			move_to(p);			// Set the values for   myColorTransform 			if (! ct) {				ct=new ColorTransform(Math.random(),Math.random(),Math.random());			}			// Associate the color transform object with the Color object 			// created for   myMovie 			this.transform.colorTransform=ct;		}		public function move_to(p: Point_ij, pClone = false) {			var pxy=p.as_point();			x=pxy.x;			y=pxy.y;			_point=p;			gotoAndPlay(1);		}		public function random_walk() {			var move_options:Vector.<Point_ij>=_point.can_go_points;			if (move_options.length) {				var ri:uint=uint(Math.random()*move_options.length);				var new_point=move_options[ri];				vector_move(new_point);			}		}		public function vector_move(new_point: Point_ij) {			var angle= _point.point_to_angle(new_point,true);			trace("angle from " + _point + " to " + new_point + " = " + angle);			pa_move(new_point, angle);		}				public var last_angle = 0;				public function circle_move(){			var angle = (last_angle + 1) % 8;			var pt: Point_ij = _point.point_at_compass_angle(angle);			pa_move(pt, angle);					}		public function pa_move(new_point: Point_ij, angle: uint) {			var angle_degrees=angle*45;			var arrow=new Move_arrow(_point,angle_degrees,this);			this.parent.addChild(arrow);			var new_pog:Pog=new Pog(new_point,this.transform.colorTransform);			new_pog.last_angle = angle;			parent.addChild(new_pog);			var NEW_POGS:Vector.<Pog>=Vector.<Pog>([new_pog]);			/* remove current pog from queue -- it becomes a "phantom"			and puts the new pog at the front of the queue to prevent redunaant			calling. */			for (var iter:uint = 0; iter < POGS.length; ++iter) {				var p:Pog=POGS[iter];				if (!((p === this) || (p === new_pog))) {					NEW_POGS.push(p);				}			}			POGS=NEW_POGS;		}		public function is_not_me(p:Pog) {			return !(this === p) ? true : false;		}	}}