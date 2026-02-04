<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once "widget/HotPost.php";
require_once "widget/TopUpContent.php";
require_once "widget/CategorySub.php";

function themeConfig($form) {
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text("logoUrl", null, null, _t("网站 Logo"), _t("在这里填写图片 URL，网站将显示 Logo"));
    $form->addInput($logoUrl->addRule("url", _t("请填写正确的 URL 地址")));
    
    $categoryNum = new \Typecho\Widget\Helper\Form\Element\Text("categoryNum", null, 5, _t("显示分类的数量"), _t("显示分类的数量，超过填数字将不显示"));
    $form->addInput($categoryNum);
    
    $customHead = new \Typecho\Widget\Helper\Form\Element\Text("customHead", null, null, _t("head 代码"), _t("自定义 head 代码"));
    $form->addInput($customHead);
    
    $customFooter = new \Typecho\Widget\Helper\Form\Element\Text("customFooter", null, null, _t("footer 代码"), _t("自定义 footer 代码"));
    $form->addInput($customFooter);
}

function themeFields($layout) {
    echo "<script src=\"" . \Typecho\Common::url("assets/main/admin.js", \Utils\Helper::options()->themeUrl) . "\"></script>";
    
    $topUp = new \Typecho\Widget\Helper\Form\Element\Radio("topUp", array("1" => _t("是"), "0" => _t("否")), 0, _t("首页置顶"), _t("是：置顶；否，不置顶"));
    $layout->addItem($topUp);
    
    $postType = new \Typecho\Widget\Helper\Form\Element\Radio("postType", array("1" => _t("标准"), "2" => _t("说说")), 1, _t("<span class=\"removeByPage\">文章类型</span>"), _t(''));
    $layout->addItem($postType);
    
    $showToc = new \Typecho\Widget\Helper\Form\Element\Radio("showToc", array("1" => _t("显示"), "0" => _t("隐藏")), 0, _t("文章目录"), _t(''));
    $layout->addItem($showToc);
    
    $thumbnail = new \Typecho\Widget\Helper\Form\Element\Text("thumbnail", null, null, _t("缩略图"), _t("填入图片地址"));
    $layout->addItem($thumbnail);

    $showPage = new \Typecho\Widget\Helper\Form\Element\Radio("showPage", array("1" => _t("是"), "0" => _t("否")), 0, _t("<span class=\"removeByPost\">在左侧显示</span>"), _t("是：页面将在左侧显示；否：隐藏"));
    $layout->addItem($showPage);

    $iconPage = new \Typecho\Widget\Helper\Form\Element\Text("iconPage", null, null, _t("<span class=\"removeByPost\">左侧显示内容</span>"), _t("填入html代码，可显示为图片、图标等。此内容由\"在左侧显示\"选项控制"));
    $layout->addItem($iconPage);
}

function postNavbarActive($archive, $slug) {
    if ($archive->is("post")) {
        $categories = $archive->categories;
        foreach ($categories as $category) {
            if ($category["slug"] === $slug) {
                return "active";
            }
        }
    }
    return '';
}

function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= " comment-by-author";
        } else {
            $commentClass .= " comment-by-user";
        }
    }
?>
    <div id="<?php $comments->theId(); ?>"
         class="py-2 <?php if ($comments->levels > 0) { 
             echo "ps-5 pb-0 comment-child"; 
             $comments->levelsAlt(" comment-level-odd", " comment-level-even"); 
         } else { 
             echo "mb-2 border-bottom border-light-subtle comment-parent"; 
         } 
         $comments->alt(" comment-odd", " comment-even"); 
         echo $commentClass; ?>">
        <div class="d-flex column-gap-3">
            <?php $comments->gravatar(50, $options->defaultAvatar, $options->avatarHighRes); ?>
            <div class="d-flex flex-column row-gap-2 flex-fill align-content-between justify-content-between">
                <div class="d-flex justify-content-between">
                    <div class="whitespace-nowrap">
                        <span class="author-name ">
                                <?php $options->beforeAuthor(); $comments->author(); $options->afterAuthor(); ?>
                        </span>
                        <time itemprop="commentTime" class="text-body-secondary"
                              datetime="<?php $comments->date("c"); ?>">
                            <?php $options->beforeDate(); $comments->date($options->dateFormat); $options->afterDate(); ?>
                        </time>
                        <?php if ("approved" !== $comments->status) { ?>
                            <em class="comment-awaiting-moderation"><?php $options->commentStatus(); ?></em>
                        <?php } ?>
                    </div>
                    <div class="comment-reply">
                        <?php $comments->reply($options->replyWord); ?>
                    </div>
                </div>
                <div class="comment-content  dark:text-gray-400 break-all">
                    <?php $comments->content(); ?>
                </div>
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </div>
    <?php
}
?>
