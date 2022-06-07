<?php

/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:54
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Prettus\Validator\Exceptions\ValidatorException;
use Facebook\Authentication\AccessToken;
use Firebase\JWT\JWT;

class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;
    private $zip_code_key = "TWesosgwNeNWcS3lA1F1wsKVGaHlkGFUFRa7qzsvWDxge2bPtOSoPrrsZFvVHyTP";
    private $client_id = 'com.therealstart.delivery';
    private $redirect_uri = 'https://deliveryapp.staging.jafton.com/api/login/apple/handle';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                $user->device_token = $request->input('device_token', '');
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
    }
    function login_with_facebook(Request $request)
    {
        $access_token = $request->input('access_token');
        $fb = new \Facebook\Facebook([
            'app_id' => '403771674854876',
            'app_secret' => '9ae85a983ab0fc6f13bd51421c7a817b',
            'default_graph_version' => 'v2.10',
        ]);
        try {
            $response = $fb->get('/me', "{$access_token}");
            $me = $response->getGraphUser();
            $email = $me->getEmail();
            $user = $this->userRepository->findByField('email', $email)->first();
            if ($user) {
                return $this->sendResponse($user, 'User retrieved successfully');
            }
            $user = new User;
            $user->name = $me->getName() . ' ' . $me->getLastName();
            $user->email = $email;
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make(str_random(60));
            $user->api_token = str_random(60);

            $user->save();

            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }


            if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                $user->addMedia(public_path('images/avatar_default_temp.png'))
                    ->withCustomProperties(['uuid' => bcrypt(str_random())])
                    ->toMediaCollection('avatar');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function login_with_google(Request $request)
    {
        try {
            $access_token = $request->input('access_token');
            $google_url = "https://www.googleapis.com/oauth2/v3/userinfo";
            $result = file_get_contents("{$google_url}?access_token={$access_token}");
            $result = json_decode($result, 1);
            if (array_key_exists('email', $result)) {
                $email = $result['email'];
                $username = str_replace('@gmail.com', '', $email);
                $user = $this->userRepository->findByField('email', $email)->first();
                if ($user) {
                    return $this->sendResponse($user, 'User retrieved successfully');
                }
                $user = new User;
                $user->name = $username;
                $user->email = $email;
                $user->device_token = $request->input('device_token', '');
                $user->password = Hash::make(str_random(60));
                $user->api_token = str_random(60);

                $user->save();

                $defaultRoles = $this->roleRepository->findByField('default', '1');
                $defaultRoles = $defaultRoles->pluck('name')->toArray();
                $user->assignRole($defaultRoles);
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }


                if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                    $user->addMedia(public_path('images/avatar_default_temp.png'))
                        ->withCustomProperties(['uuid' => bcrypt(str_random())])
                        ->toMediaCollection('avatar');
                }
            } else {
                return $this->sendError('Invalid token', 401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function get_apple_token(Request $request)
    {
        $teamId = '57W5XRTA65';

        $keyId = 'G5B6VL89U4';

        $sub = 'com.therealstart.delivery';

        $aud = 'https://appleid.apple.com'; // it's a fixed URL value

        $iat = strtotime('now');

        $exp = strtotime('+1days');

        $keyContent = "-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQgnOmmbE4tyIAwlekP
nBR2M+OPqw8H0Oax15BVOHotPJCgCgYIKoZIzj0DAQehRANCAATx0qi46Vxf9aW5
U/pZ/cYlp6UVfKrE2pw/x5AvErDCbX8a4ztJChdA4LVxnxZkIDXWnYDSHBu5WnPD
nZzXXr0X
-----END PRIVATE KEY-----";



        return JWT::encode([

            'iss' => $teamId,

            'iat' => $iat,

            'exp' => $exp,

            'aud' => $aud,

            'sub' => $sub,

        ], $keyContent, 'ES256', $keyId);
    }

    function create_apple_link(Request $request)
    {
        session_start();
        $_SESSION['state'] = bin2hex(random_bytes(5));

        $authorize_url = 'https://appleid.apple.com/auth/authorize' . '?' . http_build_query([
            'response_type' => 'code',
            'response_mode' => 'form_post',
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'state' => $_SESSION['state'],
            'scope' => 'name email',
        ]);
        return $this->sendResponse(['url' => $authorize_url], 'Link generated successfully');
    }
    function http($url, $params = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($params)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl', # Apple requires a user agent header at the token endpoint
        ]);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    function login_with_apple(Request $request)
    {
        $code = $request->input('code');
        try {
            if ($code) {

                // if ($_SESSION['state'] != $_POST['state']) {
                //     die('Authorization server returned an invalid state parameter');
                // }

                // Token endpoint docs: 
                // https://developer.apple.com/documentation/signinwithapplerestapi/generate_and_validate_tokens

                $response = $this->http('https://appleid.apple.com/auth/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->get_apple_token($request),
                    "grant_type"=> "authorization_code",
                ]);

                if (!isset($response->access_token)) {
                    return $this->sendError('Invalid token', 401);
                }
                $claims = explode('.', $response->id_token)[1];
                $claims = json_decode(base64_decode($claims));
                $email = $claims->email;
                $parts = explode("@", $email);
                $username = $parts[0];
                $user = $this->userRepository->findByField('email', $email)->first();
                if ($user) {
                    return $this->sendResponse($user, 'User retrieved successfully');
                }
                $user = new User;
                $user->name = $username;
                $user->email = $email;
                $user->device_token = $request->input('device_token', '');
                $user->password = Hash::make(str_random(60));
                $user->api_token = str_random(60);

                $user->save();

                $defaultRoles = $this->roleRepository->findByField('default', '1');
                $defaultRoles = $defaultRoles->pluck('name')->toArray();
                $user->assignRole($defaultRoles);
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }


                if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                    $user->addMedia(public_path('images/avatar_default_temp.png'))
                        ->withCustomProperties(['uuid' => bcrypt(str_random())])
                        ->toMediaCollection('avatar');
                }
            } else {
                return $this->sendError('Invalid request', 400);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user, 'User retrieved successfully');
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required',
            ]);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = str_random(60);
            $address = [];
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFieldValues = getCustomFieldsValues($customFields, $request);

            foreach ($customFieldValues as $value) {
                if ($value['key'] == "zip_code") {
                    $zip_code = $value['value'];
                    if ($zip_code) {
                        $zip_code_key = $this->zip_code_key;
                        $url = "https://www.zipcodeapi.com/rest/$zip_code_key/info.json/$zip_code/degrees";
                        try {
                            $resp = file_get_contents($url);
                        } catch (\Exception $e) {
                            return $this->sendError("Invalid zip code", 400);
                        }
                        $states = getStates();
                        if ($resp) {
                            $json = json_decode($resp);
                            $address['city'] = $json->city;
                            $address['state'] = $json->state;
                            $address['state_full'] = $json->state;
                            if (array_key_exists($address['state'], $states)) {
                                $address['state_full'] = $states[$address['state']];
                            }
                        }
                    }
                }
            }

            $user->save();

            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFieldValues = getCustomFieldsValues($customFields, $request);

            $keys = ['city', 'state', 'state_full'];
            foreach ($customFieldValues as $value) {
                foreach ($keys as $key) {
                    if ($value['key'] == $key and empty($value['value']) and isset($address[$key])) {
                        $value['value'] = $address[$key];
                        $value['view'] = $address[$key];
                    }
                }
                unset($value['key']);
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }


            if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                $user->addMedia(public_path('images/avatar_default_temp.png'))
                    ->withCustomProperties(['uuid' => bcrypt(str_random())])
                    ->toMediaCollection('avatar');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');
    }

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key(
            $settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'fcm_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
                'home_section_1' => '',
                'home_section_2' => '',
                'home_section_3' => '',
                'home_section_4' => '',
                'home_section_5' => '',
                'home_section_6' => '',
                'home_section_7' => '',
                'home_section_8' => '',
                'home_section_9' => '',
                'home_section_10' => '',
                'home_section_11' => '',
                'home_section_12' => '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                if (isset($input['avatar']) && $input['avatar']) {
                    $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                    $mediaItem = $cacheUpload->getMedia('avatar')->first();
                    if ($user->hasMedia('avatar')) {
                        $user->getFirstMedia('avatar')->delete();
                    }
                    $mediaItem->copy($user, 'avatar');
                }
                $user = $this->userRepository->update($input, $id);

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError('Reset link not sent', 401);
        }
    }
}
