<?php

function rv($name)
{
	if (isset($_REQUEST[$name])) {
		return $_REQUEST[$name];
	}

	return false;
}