<?php
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('username');
    echo $form->input('passwd', array('label' => 'Password', 'div' => 'required'));
    echo $form->end('Login');
?>