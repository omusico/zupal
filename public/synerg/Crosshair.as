﻿package  {		import flash.display.MovieClip;	import game.Point_ij;	import flash.geom.Point;		public class Crosshair extends MovieClip {				// Constants:		// Public Properties:		// Private Properties:		// UI Elements:						// Initialization:		public function Crosshair(p: Point_ij) {			var pxy : Point = p.as_point();						x = pxy.x;			y = pxy.y;						for (var i = 0; i < 8; ++i) {				if (p.can_go[i]){					var a:Arrow = new Arrow();					a.rotation = i * 45;					addChild(a);									}			}					//can_go.text = p.can_go_angles();					ij.text = p.toString();		}		// Public Methods:		// Protected Methods:		// Private Methods:		protected function configUI():void {						}	}	}