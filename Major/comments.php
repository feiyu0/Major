<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
    $depth = $comments->levels +1;
    if ($comments->url) {
        $author = '<a href="' . $comments->url . '"target="_blank"' . ' rel="external nofollow">' . $comments->author . '</a>';
    } else {
        $author = $comments->author;
    }
    ?>

    <li id="li-<?php $comments->theId(); ?>" class="comment-list-item comment even thread-even depth-<?php echo $depth ?> comment-body<?php
    if ($comments->levels > 0) {
        echo ' comment-child';
        $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
    } else {
        echo ' comment-parent';
    }
    $comments->alt(' comment-odd', ' comment-even');
    ?>">
        <article id="<?php $comments->theId(); ?>" class="comment-body">
            <footer class="comment-meta" data-toggle="tooltip"
                    data-placement="left" title="[<?php echo $comments->date();?>][<?php echo "IP:".$comments->ip;?>]">
                <div class="comment-author vcard">
                    <?php $comments->gravatar(40); ?>
                    <b class="fn <?php echo $commentClass; ?> " itemprop="author">
                        <?php echo $author; ?>
                    </b>
                </div>
                <!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="" itemprop="url">
                        <time class="liveTime" id="liveTime" data-lta-value="<?php $comments->date('c'); ?>"></time>
                    </a>
                </div>
                <!-- .comment-metadata -->

            </footer>
            <!-- .comment-meta -->

            <div class="comment-content  major-text">
                <?php $comments->content(); ?>
            </div>
            <!-- .comment-content -->

            <div class="comment-actions">
                <?php $comments->reply('回复'); ?>
                <!-- .comment-actions -->
            </div>
        </article>

        <?php if ($comments->children) { ?>
            <div class="children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<div id="comments" data-no-instant>
    <div class="comment-respond">
    <?php $this->comments()->to($comments); ?>

        <div class="comments-title">
            <span class="comment-num"><?php $this->commentsNum(_t('暂无评论'), _t('仅有 1 条评论'), _t('已有 %d 条评论')); ?></span>
        </div>

        <?php if($this->allow('comment')): ?>
            <div id="<?php $this->respondId(); ?>" class="respond">
                <div class="cancel-comment-reply">
                    <?php $comments->cancelReply(); ?>
                </div>
                <h4 id="response" class="comment-reply-title">
                    <span>发表评论</span>
                    <small>
                        <span class="response">
                            <?php if($this->user->hasLogin()): ?>
                                <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>(已登录) <a href="<?php $this->options->logoutUrl(); ?>" class="icon-logout"></a>
                            <?php else: ?>
                                <span id="noUserText">你是访客</span> <a href="<?php $this->options->loginUrl(); ?>" class="icon-login"></a>
                            <?php endif; ?>
                        </span>
                    </small>
                    <?php $comments->cancelReply('取消回复'); ?>
                </h4>
                <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="comment-form" role="form">
                    <div class="author-infos guest" id="comment-form-avatar"><img src="<?php Typecho_Widget::widget('Widget_Options')->plugin('majors')->serverGravatar();?>/?d=mm&s=100" width="100" class="avatar"></div>
                    <div class="comment-form-main">
                        <div class="comment-textarea-wrapper rippleria-dark" data-rippleria>
                            <p class="comment-form-comment"><label for="comment">评论</label>
                                <textarea style="" id="textarea" name="text"  <?php if(!$this->user->hasLogin()): ?> onclick='document.getElementById("comment-form-do").style.display="block";'<?php endif; ?>  cols="45" rows="8" aria-required="true" required="required" placeholder="发泄你的牢骚,留下你的笔言!"><?php $this->remember('text',false); ?></textarea>
                            </p>
                            <div class="comment-form-toolbar">
                              <?php if(isset($this->options->plugins['activated']['Smilies'])) Smilies_Plugin::output(); ?>
                              
                            </div>
                        </div>

                        <?php if(!$this->user->hasLogin()): ?>
                            <div class="comment-form-fields" id="comment-form-do">
                                <p class="comment-form-author">
                                    <label for="author">昵称</label> <span class="required">*</span>

                                    <input type="text" name="author" maxlength="12" id="author" placeholder="昵称" value="" required>

                                </p>
                                <p class="comment-form-email parentCls">
                                    <label for="email">邮箱</label> <?php if ($this->options->commentsRequireMail): ?><span class="required">*</span><?php endif; ?>

                                    <input type="email" name="mail" id="mail" placeholder="邮箱" value="" class="inputElem" <?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                                </p>
                                <p class="comment-form-url">
                                    <label for="url">网站</label> <?php if ($this->options->commentsRequireURL): ?><span class="required">*</span><?php endif; ?>

                                    <input type="url" name="url" id="url" placeholder="网站" value="" <?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?>>

                                </p>
                                <p class="comment-form-fast">
                                    <label for="url">输入QQ号快速评论</label>
                                    <input placeholder="输入QQ号快速评论" id="qqNum" type="text">
                                </p>
                            </div>
                        <?php endif; ?>

                        <p class="form-submit">
                            <button name="submit" type="submit" id="submit" class="submit"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-send"></use></svg></button>
                            <?php $security = $this->widget('Widget_Security'); ?>
                            <input type="hidden" name="_" value="<?php echo $security->getToken($this->request->getReferer())?>">
                        </p>
                    </div>
                    <div class="comment-form-extra">

                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($comments->have()): ?>
            <?php $comments->listComments(); ?>
            <?php $comments->pageNav('&laquo;', '&raquo;'); ?>

        <?php endif; ?>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#textarea').textareafullscreen();
    });

    function iasNew() {
        try
        {
            var iasCent = jQuery.ias({
                container:  '.comment-list',    //大容器
                item:       '.comment-list-item.depth-1',    //循环容器
                pagination: '.page-navigator li',    //分页容器
                next:       '.next a'    //下一页的class
            });

            iasCent.extension(new IASTriggerExtension({
                html: '<div class="iasBtn"><button class="mdui-textfield-icon mdui-btn mdui-btn-icon" role="button" data-no-instant><i class="mdui-icon material-icons">add</i></button></div>',
                offset: 1 //load多少页后显示加载更多按钮
            }));
            iasCent.extension(new IASSpinnerExtension());    //加载时的图片
            iasCent.extension(new IASNoneLeftExtension({text: "已经没有更多评论了"}));    //到底后显示的文字

            iasCent.on('rendered', function(items) {
                liveTimeGo();
            });
        }
        catch(err) {}
    }
    iasNew();
</script>

<script>
    <?php if(!$this->user->hasLogin()): ?>
    function getCommentCookie(name){
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(decodeURI(arr[2]));
        else
            return null;
    }
    function addCommentInputValue(){
        var authorGet = getCommentCookie('<?php echo md5($this->request->getUrlPrefix()); ?>__typecho_remember_author');
        var md5Get = '<?php echo md5($this->request->getUrlPrefix()); ?>';
        document.getElementById('author').value = authorGet;
        if(authorGet){
            document.getElementById("noUserText").innerHTML= '你好,'+authorGet;
        }
        document.getElementById('mail').value = getCommentCookie(md5Get+'__typecho_remember_mail');
        document.getElementById('url').value = getCommentCookie(md5Get+'__typecho_remember_url');
        document.getElementById("comment-form-avatar").getElementsByTagName("img")[0].src = "<?php Typecho_Widget::widget('Widget_Options')->plugin('majors')->serverGravatar();?>/" + md5(getCommentCookie(md5Get+'__typecho_remember_mail')) + "?s=100&r=G&d=mm";
    }

    $(document).on("input propertychange", "#qqNum", function(event) {
        event.preventDefault();
        var tval = $(this).val();
        var mt = window.setTimeout(function() {
            var nval = $("#qqNum").val();
            if (nval.length > 0 && tval == $("#qqNum").val()) {
                $.ajax({
                    url: '<?php $this->options->index("obtain/quik"); ?>?qq=' + nval,
                    dataType: 'jsonp',
                    jsonpCallback: 'portraitCallBack',
                    scriptCharset: "GBK",
                    contentType: "text/html; charset=GBK",
                    success: function(data) {
                        console.log('portraitCallBack success:'+data[nval][6]+' and '+nval + '@qq.com');
                        document.getElementById("comment-form-avatar").getElementsByTagName("img")[0].src = 'https://q2.qlogo.cn/headimg_dl?dst_uin='+nval+'&spec=100';
                        $('#author').val(data[nval][6]);
                        $('#mail').val(nval + '@qq.com');
                        $('#url').val('https://user.qzone.qq.com/'+nval);
                        document.getElementById("noUserText").innerHTML= '你好,'+data[nval][6];
                    }
                })
            }
        }, 400)
    });

    addCommentInputValue();
    <?php else:?>
        document.getElementById("comment-form-avatar").getElementsByTagName("img")[0].src = "<?php Typecho_Widget::widget('Widget_Options')->plugin('majors')->serverGravatar();?>/" + md5("<?php $this->author->mail(); ?>") + "?s=100&r=G&d=mm";
    <?php endif; ?>

    (function () {
        window.TypechoComment = {
            dom : function (id) {
                return document.getElementById(id);
            },
            create : function (tag, attr) {
                var el = document.createElement(tag);
                for (var key in attr) {
                    el.setAttribute(key, attr[key]);
                }
                return el;
            },
            reply : function (cid, coid) {
                var comment = this.dom(cid), parent = comment.parentNode,
                    response = this.dom('<?php echo $this->respondId(); ?>'),
                    input = this.dom('comment-parent'),
                    form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                    textarea = response.getElementsByTagName('textarea')[0];
                if (null == input) {
                    input = this.create('input', {
                        'type' : 'hidden',
                        'name' : 'parent',
                        'id'   : 'comment-parent'
                    });
                    form.appendChild(input);
                }
                input.setAttribute('value', coid);
                if (null == this.dom('comment-form-place-holder')) {
                    var holder = this.create('div', {
                        'id' : 'comment-form-place-holder'
                    });
                    response.parentNode.insertBefore(holder, response);
                }
                comment.appendChild(response);
                this.dom('cancel-comment-reply-link').style.display = '';
                if (null != textarea && 'text' == textarea.name) {
                    textarea.focus();
                }
                return false;
            },
            cancelReply : function () {
                var response = this.dom('<?php echo $this->respondId(); ?>'),
                    holder = this.dom('comment-form-place-holder'),
                    input = this.dom('comment-parent');
                if (null != input) {
                    input.parentNode.removeChild(input);
                }
                if (null == holder) {
                    return true;
                }
                this.dom('cancel-comment-reply-link').style.display = 'none';
                holder.parentNode.insertBefore(response, holder);
                return false;
            }
        };
    })();
</script>
<script type = "text/javascript" data-no-instant>
    (function() {
        var event = document.addEventListener ? {
            add: 'addEventListener',
            focus: 'focus',
            load: 'DOMContentLoaded'
        } : {
            add: 'attachEvent',
            focus: 'onfocus',
            load: 'onload'
        };
        document[event.add](event.load, function() {
            var r = document.getElementById('<?php echo $this->respondId(); ?>');
            if (null != r) {
                var forms = r.getElementsByTagName('form');
                if (forms.length > 0) {
                    var f = forms[0],
                        textarea = f.getElementsByTagName('textarea')[0],
                        added = false;
                    if (null != textarea && 'text' == textarea.name) {
                        textarea[event.add](event.focus, function() {
                            if (!added) {
                                var input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = '_';
                                input.value = (function() {
                                    var _a8C5A = //'xr'
                                            '8d0' + //'vI'
                                            'vI' + /* 'mj'//'mj' */ '' + //'P'
                                            '06' + 'd' //'chS'
                                            + //'wo'
                                            '0ef' + '41' //'9G'
                                            + '8c8' //'R'
                                            + //'p1'
                                            'd0' + //'mi'
                                            'mi' + 'baf' //'lu'
                                            + 'c' //'dm'
                                            + //'ED'
                                            '1a9' + //'Lh'
                                            'd9' + '6' //'luM'
                                            + //'xH'
                                            'f1' + //'W'
                                            '2c7' + 'f' //'f'
                                            + //'9'
                                            '9' + //'Nd'
                                            'Nd' + /* '8ys'//'8ys' */ '' + '' ///*'6Yc'*/'6Yc'
                                            + //'H'
                                            '0',
                                        _LceE8M = [
                                            [3, 5],
                                            [16, 18],
                                            [31, 32],
                                            [31, 32],
                                            [31, 33]
                                        ];
                                    for (var i = 0; i < _LceE8M.length; i++) {
                                        _a8C5A = _a8C5A.substring(0, _LceE8M[i][0]) + _a8C5A.substring(_LceE8M[i][1]);
                                    }
                                    return _a8C5A;
                                })();
                                f.appendChild(input);
                                added = true;
                            }
                        });
                    }
                }
            }
        });
    })();
</script><!--</nocompress>-->