<?php

namespace MEC\SingleBuilder\Widgets\Content;

use MEC\Base;
use MEC\SingleBuilder\Widgets\WidgetBase;

class Content extends WidgetBase {

	/**
	 *  Get HTML Output
	 *
	 * @param int $event_id
	 * @param array $atts
	 *
	 * @return string
	 */
	public function output( $event_id = 0, $atts = array() ){

		if( !$event_id ){

			$event_id = $this->get_event_id();
		}

		if(!$event_id){
			return '';
		}

		$content = wpautop( do_shortcode( get_the_content( '', false, $event_id ) ) );

		$html = '<div class="mec-single-event-description mec-events-content">'
			. $content .
		'</div>';

		return $html;
	}
}
