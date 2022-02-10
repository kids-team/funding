<?php

namespace Contexis\Funding;

class Altruja {

	public static function render_block($attributes) {
		return '<div class=""><iframe id="altruja" src="https://em.altruja.de/' . $attributes['eid'] . '/spende?"></iframe></div>';
	}

}