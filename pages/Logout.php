<?php
unset($_SESSION['user_id']);
header('Location: ./?page=login');
