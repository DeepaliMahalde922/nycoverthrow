<?php

class MGLInstagramGallery_Notice_Notice
{

    private $flashMessageBag = array();

    public function addFlashMessage($message, $message_type)
    {
        $this->flashMessageBag[] = array($message_type => $message);
    }//addFlashMessage END

    public function render_flash_message_bag()
    {
        foreach ($this->flashMessageBag as $flashMessage) {
            foreach ($flashMessage as $message_type => $message) {
                ?>
                <div class="notice notice-<?php echo $message_type; ?>">
                    <p><strong><?php echo $message ?></strong></p>
                </div>
                <?php
            }
        }
        $this->flashMessageBag = array();
    }//render_flash_message_bag END

}

//Notice Service global functions
function get_instagram_notice_service()
{
    static $notice_service;

    if (null !== $notice_service) {
        return $notice_service;
    }

    $notice_service = new MGLInstagramGallery_Notice_Notice();
    return $notice_service;
}

function mgl_instagram_add_flash_message($message, $message_type = 'warning')
{
    $messages = get_instagram_notice_service();
    $messages->addFlashMessage($message, $message_type);
}

function mgl_instagram_render_flash_message_bag()
{
    $messages = get_instagram_notice_service();
    $messages->render_flash_message_bag();
}
