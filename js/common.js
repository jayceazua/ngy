// JavaScript Document
function set_tab_class(div_nm,clasnm){
	document.getElementById(div_nm).className = clasnm;
}

function jump_page(p, gotopagenm, extraqry){
	location.href = gotopagenm + '?p=' + p + extraqry;
}

function pageitemgo(){
 location.href = document.pgnat.gotourl.value + "&pageitem=" + document.pgnat.pageitem.value;
}
function pageitemgo_1(){
 location.href = document.pgnat_1.gotourl.value + "&pageitem=" + document.pgnat_1.pageitem.value;
}
   
function pageitemgo_sort(){
 location.href = document.pgnat.gotourl.value + "&sby=" + document.pgnat_sort.sby.value + "&pageitem=" + document.pgnat.pageitem.value;
}
function pageitemgo_sort_1(){
 location.href = document.pgnat_1.gotourl.value + "&sby=" + document.pgnat_sort_1.sby.value + "&pageitem=" + document.pgnat_1.pageitem.value;
}