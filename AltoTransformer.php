<?php

interface AltoTransformer {
	/**
	 * Transform a paraemter headed from a URL (i.e. during matching)
	 *
	 * @param mixed $value The value to transform
	 * @return mixed The transformed value
	 */
	public function fromUrl($value);

	/**
	 * Transform a parameter headed to a URL (i.e. during generation)
	 *
	 * @param mixed $value The value to transform
	 * @return mixed The transformed value
	 */
	public function toUrl($value);
}
