<?php
function setActive($path)
{
    return Request::is($path .'') ? 'active' :  '';
}

function setActived($path)
{
    return Request::is($path .'*') ? 'active' :  '';
}