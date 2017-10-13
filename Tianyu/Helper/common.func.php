<?php

function Lang(){

}

function checkErr($info){
	if (!isset($info['code'])) {
		return false;
	}

	if (!isset($info['msg'])) {
		return false;
	}

	if ($info['code'] == 200){
		return false;
	}

	return $info;
}