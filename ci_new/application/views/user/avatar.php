<?php
$avatar = 'nobody.gif';
if (isset($avatar_info) && $avatar_info->avatar && file_exists('./images/avatars/' . $avatar_info->avatarImage)) {
    $avatar = $avatar_info->avatarImage;
    $username = $avatar_info->username;
}
elseif (isset($user_profile)) {
    $username = $user_profile['username'];
    if ($user_profile['avatar'] && file_exists('./images/avatars/' . $user_profile['avatarImage'])) {
        $avatar = $user_profile['avatarImage'];
    }
}
?>
    <div class="userAvatar" style="position: relative;">
        <img src="/images/avatars/<?php echo $avatar; ?>" title="<?php echo $username; ?> avatar picture" alt="<?php echo $username; ?> avatar picture">
<?php
if (isset($nubbin) && $nubbin && $id == $user_info['id']) {
?>
        <div id="nubbin_<?php echo $id; ?>" class="nubbin" style="position: absolute; top: -5px;">
            <a href="<?php echo base_url(); ?>page/uploadImage/avatars/<?php echo $id; ?>">
                <img src="/images/nubbin_editPhoto.jpg" title="edit image" alt="edit image">
            </a>
        </div>
<?php
}
?>
    </div>

