<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Tinymce 编辑器 ，基于Tinymce5.10.0版本制作
 * 
 * @package Tinymce
 * @author 泽泽社长
 * @version 1.2.0
 * @link https://store.typecho.work
 */
class Tinymce_Plugin implements Typecho_Plugin_Interface
{

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('admin/write-post.php')->richEditor =  __CLASS__ . '::Editor';
        \Typecho\Plugin::factory('admin/write-page.php')->richEditor =  __CLASS__ . '::Editor';
        \Typecho\Plugin::factory('admin/write-post.php')->bottom =  __CLASS__ . '::js';
        \Typecho\Plugin::factory('admin/write-page.php')->bottom =  __CLASS__ . '::js';
        \Typecho\Plugin::factory('Widget_Upload')->upload_16 =  __CLASS__ . '::uploadend';
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {


    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}


    public static function uploadend($obj)
    {                    
        $obj->response->throwJson([$obj->attachment->url, [
            'cid' => $obj->cid,
            'title' => $obj->attachment->name,
            'type' => $obj->attachment->type,
            'size' => $obj->attachment->size,
            'bytes' => number_format(ceil($obj->attachment->size / 1024)) . ' Kb',
            'isImage' => $obj->attachment->isImage,
            'url' => $obj->attachment->url,
            'permalink' => $obj->permalink
        ],
        'location' =>$obj->attachment->url,
        'cid' =>  $obj->cid,
        'title' => $obj->attachment->name,
        'type' => $obj->attachment->type,
        'size' => $obj->attachment->size,
        'bytes' => number_format(ceil($obj->attachment->size / 1024)) . ' Kb',
        'isImage' => $obj->attachment->isImage,
        'url' => $obj->attachment->url,
        'permalink' => $obj->permalink,
    ]);
    }
    /**
     * 插入编辑器
     */
    public static function Editor($post)
    {
        $post->isMarkdown=0;
        ?>
<style>
.tox-fullscreen #wmd-button-bar, .tox-fullscreen #text, .tox-fullscreen #wmd-preview, .tox-fullscreen .submit { position: absolute; top: 0; width: auto; background: #FFF; z-index: 1201; box-sizing: border-box; border-radius: 0; }
.tox-fullscreen #wmd-button-bar, .tox-fullscreen #text, .tox-fullscreen #wmd-preview, .tox-fullscreen .submit { background: rgba(255,255,255,0);}
.resize,.tox-fullscreen #btn-preview,.upload-area{display:none;}
.upload-areax { padding: 15px; text-align: center; }
.tox-fullscreen .typecho-post-area .right {padding-left: 0;}
.fullscreen #wmd-button-bar { left: 0; padding: 13px 20px; border-bottom: 1px solid #F3F3F0; z-index: 1000; }

.tox-fullscreen #text { top: 53px; left: 0; padding: 20px; border: none; outline: none; }

.tox-fullscreen #wmd-preview { top: 53px; right: 0; margin: 0; padding: 5px 20px; border: none; border-left: 1px solid #F3F3F0; background: #F6F6F3; overflow: auto; }

.tox-fullscreen .submit { right: 0; margin: 0; padding: 10px 20px; border: 1px solid #F3F3F0; }

.tox-fullscreen #upload-panel { -webkit-box-shadow: 0 4px 16px rgba(0, 0, 0, 0.225); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.225); border-style: solid; }

.tox-fullscreen #tab-files { position: absolute; top: 52px; right: 0; width: 280px; z-index: 1201; animation: fullscreen-upload 0.5s; }

.tox-fullscreen .wmd-edittab, .tox-fullscreen .typecho-post-option, .tox-fullscreen .title, .tox-fullscreen .url-slug, .tox-fullscreen .typecho-page-title, .tox-fullscreen .typecho-head-nav, .tox-fullscreen .message { display: none; }

.tox-fullscreen .wmd-hidetab { display: block; }

.tox-fullscreen .wmd-visualhide, .tox-fullscreen #btn-fullscreen-upload { visibility: visible; }
.mce-nbsp, .mce-shy {
    background: #fff !important;
}
</style>
        <?php
    }
    public static function js($post,$p)
    {
       $p = empty($p)?$post:$p;
       if($post instanceof \Typecho\Widget && $post->have()) {
        $fileParentContent=$post;
       }

       
       $options = Helper::options();
       $URL=$options->pluginUrl.'/Tinymce/';
        ?>
        <script type="text/javascript" src="<?PHP ECHO $URL; ?>tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="<?PHP ECHO $URL; ?>zh_CN.js"></script>
        
                <script>
		tinymce.init({
	    selector: '#text', //容器，可使用css选择器
	    language:'zh_CN', //调用放在langs文件夹内的语言包
	    toolbar: true, //工具栏
	    menubar: true, //菜单栏
	    branding:false, //右下角技术支持
	    inline: false, //开启内联模式
	    elementpath: false,
	    min_height:500, //最小高度
	    height: 800,  //高度
	    toolbar_sticky:true,
	    visualchars_default_state:true, //显示不可见字符
	    image_caption: true,
	    paste_data_images: true,
	    relative_urls : false,
 //skin: 'oxide-dark',
 //content_css: 'dark',
	   // remove_script_host : false,
	    removed_menuitems: 'newdocument',  //清除“文件”菜单
	    plugins: "lists,hr, advlist,anchor,autolink,autoresize,charmap,code,codesample,emoticons,fullscreen,media,insertdatetime,link,image,paste,preview,searchreplace,table,textcolor,toc,visualchars,wordcount", //依赖lists插件
	    toolbar: 'bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | bullist numlist anchor charmap emoticons hr link image media paste searchreplace textcolor wordcount preview fullscreen',
	 
	    //选中时出现的快捷工具，与插件有依赖关系 
	    //images_upload_url:'<?php Helper::security()->index('/action/upload'
                . (isset($fileParentContent) ? '?cid=' . $fileParentContent->cid : '')); ?>', /*后图片上传接口*/ /*返回值为json类型 {'location':'uploads/jpg'}*/
        images_upload_handler: function(blobInfo, success, failure, progress) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '<?php Helper::security()->index('/action/upload'
                . (isset($fileParentContent) ? '?cid=' . $fileParentContent->cid : '')); ?>');
 
        xhr.upload.onprogress = function(e) {
            progress(e.loaded / e.total * 100);
        }
 
        xhr.onload = function() {
            var json;
            if (xhr.status == 403) {
                failure('HTTP Error: ' + xhr.status, {
                    remove: true
                });
                return;
            }
            if (xhr.status < 200 || xhr.status >= 300) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
            $("#file-list").append('<li data-cid="' + json.cid + '" data-url="'+json.location+'" data-image="1"><input type="hidden" name="attachment[]" value="' + json.cid + '"><a class="insert" title="点击插入文件" href="###">'+json.title+'</a><div class="info">'+json.bytes+'<a class="file" target="_blank" href="<?php Helper::options()->adminUrl('media.php'); ?>?cid=' + json.cid + '" title="编辑"><i class="i-edit"></i></a><a href="###" class="delete" title="删除"><i class="i-delete"></i></a></div></li>');

            //更新附件数量
            var btn = $('#tab-files-btn'),
            balloon = $('.balloon', btn),
            count = $('#file-list li .insert').length;

        if (count > 0) {
            if (!balloon.length) {
                btn.html($.trim(btn.html()) + ' ');
                balloon = $('<span class="balloon"></span>').appendTo(btn);
            }

            balloon.html(count);
        } else if (0 == count && balloon.length > 0) {
            balloon.remove();
        }
        $('#file-list li').each(function () {
        attachInsertEventx(this);
        attachDeleteEventx(this);
    });



        };
 
        xhr.onerror = function() {
            failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        }
 
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
 
        xhr.send(formData);
    },
 	    setup: function(editor){ 
	   		 editor.on('change',function(){ editor.save(); });

		},
        init_instance_callback: (editor) => {
            //$('#text').show();
            //var textarea = document.getElementById('text'); // 替换为你的textarea元素的ID  
        //textarea.addEventListener('input', function() {
           
          //  editor.setContent(textarea.value)
         //  });
  },
 
		  mobile: {menubar: true}

	});


    function attachDeleteEventx (el) {
        var file = $('a.insert', el).text();
        $('.delete', el).click(function () {
            if (confirm('确认要删除文件 %s 吗?'.replace('%s', file))) {
                var cid = $(this).parents('li').data('cid');
                $.post('<?php Helper::security()->index('/action/contents-attachment-edit'); ?>',
                    {'do' : 'delete', 'cid' : cid},
                    function () {
                        $(el).fadeOut(function () {
                            $(this).remove();
                            updateAttacmentNumber();
                        });
                    });
            }

            return false;
        });
    }
    function attachInsertEventx (el) {
        $('.insert', el).click(function () {
            var t = $(this), p = t.parents('li');
            var url= p.data('url'),file=t.text();
            if(p.data('image')){
            tinyMCE.execCommand("mceReplaceContent",false,"<img src=" + url + " alt=" + file + " />");
            }else{
                tinyMCE.execCommand("mceReplaceContent",false,"<a href=" + url + ">" + file + "</a>");
            }
            return false;
        });
    }
	$(document).ready(function () {         
    $('#file-list li').each(function () {
        attachInsertEventx(this);
    });


    $(".upload-area").replaceWith('<div class="upload-areax" draggable="true">拖放文件到这里<br>或者 <a href="###" class="upload-filex">选择文件上传</a></div>');
    

    function updateAttacmentNumber () {
        var btn = $('#tab-files-btn'),
            balloon = $('.balloon', btn),
            count = $('#file-list li .insert').length;

        if (count > 0) {
            if (!balloon.length) {
                btn.html($.trim(btn.html()) + ' ');
                balloon = $('<span class="balloon"></span>').appendTo(btn);
            }

            balloon.html(count);
        } else if (0 == count && balloon.length > 0) {
            balloon.remove();
        }
    }

    $('.upload-area').bind({
        dragenter   :   function () {
            $(this).parent().addClass('drag');
        },

        dragover    :   function (e) {
            $(this).parent().addClass('drag');
        },

        drop        :   function () {
            $(this).parent().removeClass('drag');
        },
        
        dragend     :   function () {
            $(this).parent().removeClass('drag');
        },

        dragleave   :   function () {
            $(this).parent().removeClass('drag');
        }
    });

    updateAttacmentNumber();

    function fileUploadStart (file) {
        $('<li id="' + file.id + '" class="loading">'
            + file.name + '</li>').appendTo('#file-list');
    }

    function fileUploadError (error) {
        var file = error.file, code = error.code, word; 
        
        switch (code) {
            case plupload.FILE_SIZE_ERROR:
                word = '文件大小超过限制';
                break;
            case plupload.FILE_EXTENSION_ERROR:
                word = '文件扩展名不被支持';
                break;
            case plupload.FILE_DUPLICATE_ERROR:
                word = '文件已经上传过';
                break;
            case plupload.HTTP_ERROR:
            default:
                word = '上传出现错误';
                break;
        }

        var fileError = '%s 上传失败'.replace('%s', file.name),
            li, exist = $('#' + file.id);

        if (exist.length > 0) {
            li = exist.removeClass('loading').html(fileError);
        } else {
            li = $('<li>' + fileError + '<br />' + word + '</li>').appendTo('#file-list');
        }

        li.effect('highlight', {color : '#FBC2C4'}, 2000, function () {
            $(this).remove();
        });

        // fix issue #341
        this.removeFile(file);
    }

    var completeFile = null;
    function fileUploadComplete (id, url, data) {
        var li = $('#' + id).removeClass('loading').data('cid', data.cid)
            .data('url', data.url)
            .data('image', data.isImage)
            .html('<input type="hidden" name="attachment[]" value="' + data.cid + '" />'
                + '<a class="insert" target="_blank" href="###" title="点击插入文件">' + data.title + '</a><div class="info">' + data.bytes
                + ' <a class="file" target="_blank" href="<?php Helper::options()->adminUrl('media.php'); ?>?cid=' 
                + data.cid + '" title="编辑"><i class="i-edit"></i></a>'
                + ' <a class="delete" href="###" title="删除"><i class="i-delete"></i></a></div>')
            .effect('highlight', 1000);
            
        attachInsertEventx(li);
        attachDeleteEventx(li);
        updateAttacmentNumber();

        if (!completeFile) {
            completeFile = data;
        }
    }

    var uploader = null, tabFilesEl = $('#tab-files').bind('init', function () {
        uploader = new plupload.Uploader({
            browse_button   :   $('.upload-filex').get(0),
            url             :   '<?php Helper::security()->index('/action/upload'
                . (isset($fileParentContent) ? '?cid=' . $fileParentContent->cid : '')); ?>',
            runtimes        :   'html5,flash,html4',
            flash_swf_url   :   '<?php Helper::options()->adminStaticUrl('js', 'Moxie.swf'); ?>',
            drop_element    :   $('.upload-areax').get(0),
            filters         :   {
                max_file_size       :   '200mb',
                mime_types          :   [{'title' : '允许上传的文件', 'extensions' : 'gif,jpg,jpeg,png,tiff,bmp,webp,mp3,mp4,mov,wmv,wma,rmvb,rm,avi,flv,ogg,oga,ogv,txt,doc,docx,xls,xlsx,ppt,pptx,zip,rar,pdf,svg'}],
                prevent_duplicates  :   true
            },

            init            :   {
                FilesAdded      :   function (up, files) {
                    for (var i = 0; i < files.length; i ++) {
                        fileUploadStart(files[i]);
                    }

                    completeFile = null;
                    uploader.start();
                },

                UploadComplete  :   function () {
                    if (completeFile) {
                        Typecho.uploadComplete(completeFile);
                    }
                },

                FileUploaded    :   function (up, file, result) {
                    if (200 == result.status) {
                        var data = $.parseJSON(result.response);

                        if (data) {
                            fileUploadComplete(file.id, data[0], data[1]);
                            uploader.removeFile(file);
                            return;
                        }
                    }

                    fileUploadError.call(uploader, {
                        code : plupload.HTTP_ERROR,
                        file : file
                    });
                },

                Error           :   function (up, error) {
                    fileUploadError.call(uploader, error);
                }
            }
        });

        uploader.init();
    });
	});
	</script>
	<?php
        return $p;
    }

}
