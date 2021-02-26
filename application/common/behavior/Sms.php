<?php

namespace app\common\behavior;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Exception;
use think\Log;

class Sms
{

    public function notice(&$params)
    {
        try {
            AlibabaCloud::accessKeyClient(config('sms.accessKeyId'), config('sms.accessSecret'))->regionId('cn-hangzhou')
            ->asDefaultClient();
            
            $result = AlibabaCloud::rpc()
                    ->product('Dysmsapi')
                    // ->scheme('https') // https | http
                    ->version('2017-05-25')
                    ->action('SendSms')
                    ->method('POST')
                    ->host('dysmsapi.aliyuncs.com')
                    ->options([
                        'query' => [
                            'RegionId'      => "cn-hangzhou",
                            'PhoneNumbers'  => $params['mobile'],
                            'SignName'      => config('sms.signName'),
                            'TemplateCode'  => $params['template'],
                            'TemplateParam' => is_array($params['params']) ? json_encode($params['params']) : $params['params'],
                        ]
                    ])
                    ->request();
            if ($result['Code'] != 'OK') {
                Log::write('Sms send error:', Log::ERROR);
                Log::write($result['Message'], Log::ERROR);
                return false;
            }
        } catch (ClientException $e) {
            Log::write('Sms send ClientException:' . $e->getErrorMessage(), Log::ERROR);
            return false;
        } catch (ServerException $e) {
            Log::write('Sms send ServerException:' . $e->getErrorMessage(), Log::ERROR);
            return false;
        } catch (Exception $e) {
            Log::write('Sms send Exception:' . $e->getMessage(), Log::ERROR);
            return false;
        }
        
        return true;
    }

}