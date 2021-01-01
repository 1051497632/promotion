<?php

namespace app\manage\library;

use app\common\exception\UploadException;
use app\common\library\Upload as LibraryUpload;
use app\common\model\Attachment;
use think\File;

/**
 * 文件上传类
 */
class Upload extends LibraryUpload
{

    /**
     * 普通上传
     * @return \app\manage\model\attachment|\think\Model
     * @throws UploadException
     */
    public function upload($savekey = null)
    {
        if (empty($this->file)) {
            throw new UploadException(__('No file upload or server upload limit exceeded'));
        }

        $this->checkSize();
        $this->checkExecutable();
        $this->checkMimetype();
        $this->checkImage();

        $savekey = $savekey ? $savekey : $this->getSavekey();
        $savekey = '/' . ltrim($savekey, '/');
        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);

        $destDir = ROOT_PATH . 'public' . str_replace('/', DS, $uploadDir);

        $sha1 = $this->file->hash();

        //如果是合并文件
        if ($this->merging) {
            if (!$this->file->check()) {
                throw new UploadException($this->file->getError());
            }
            $destFile = $destDir . $fileName;
            $sourceFile = $this->file->getRealPath() ?: $this->file->getPathname();
            $info = $this->file->getInfo();
            $this->file = null;
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0755, true);
            }
            rename($sourceFile, $destFile);
            $file = new File($destFile);
            $file->setSaveName($fileName)->setUploadInfo($info);
        } else {
            $file = $this->file->move($destDir, $fileName);
            if (!$file) {
                // 上传失败获取错误信息
                throw new UploadException($this->file->getError());
            }
        }
        $this->file = $file;
        $manageId = (int)session('manage.id');
        $params = array(
            'admin_id'    => 0,
            'user_id'     => $manageId,
            'filename'    => substr(htmlspecialchars(strip_tags($this->fileInfo['name'])), 0, 100),
            'filesize'    => $this->fileInfo['size'],
            'imagewidth'  => $this->fileInfo['imagewidth'],
            'imageheight' => $this->fileInfo['imageheight'],
            'imagetype'   => $this->fileInfo['suffix'],
            'imageframes' => 0,
            'mimetype'    => $this->fileInfo['type'],
            'url'         => $uploadDir . $file->getSaveName(),
            'uploadtime'  => time(),
            'storage'     => 'local',
            'sha1'        => $sha1,
            'extparam'    => '',
        );
        $attachment = new Attachment();
        $attachmentInfo = $attachment->where('url', $params['url'])->where('user_id', $manageId)->find();
        $params = array_filter($params);
        if ($attachmentInfo) {
            $attachmentInfo->save($params);
        } else {
            $attachmentInfo = Attachment::create($params, true);
        }
        
        \think\Hook::listen("upload_after", $attachmentInfo);
        return $attachmentInfo;
    }
}
