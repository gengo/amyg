<?php

/**
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * The myGengo plugin allows users to order translation services from myGengo website.
 *
 * @package mygengo
 *
 * @author Tito Bouzout <http://titobouzout.github.com/amyg/>
 * @version 1.0.6
 * @since Frog version 0.9.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 */

	Plugin::setInfos(array(
		'id'          => 'mygengo',
		'title'       => 'myGengo', 
		'description' => 'Order translations from myGengo services directly from the admin interface. Allows you to request human translation of your website content such blog post, etc.', 
		'version'     => '1.0.6',
		'license'     => 'GPL',
		'author'      => 'Tito Bouzout',
		'website'     => 'http://titobouzout.github.com/amyg/'
	));

	Plugin::addController('mygengo', 'myGengo', 'developer,editor');

?>