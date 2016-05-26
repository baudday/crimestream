<?php

function neverSubscribed($user) {
  return is_null($user->subscription('main'));
}
