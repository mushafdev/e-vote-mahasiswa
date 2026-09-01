<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class RememberMe {

    public static function generate_tokens(): array
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));

        return [$selector, $validator, $selector . ':' . $validator];
    }

    public static function parse_token(string $token): ?array
    {
        $parts = explode(':', $token);

        if ($parts && count($parts) == 2) {
            return [$parts[0], $parts[1]];
        }
        return null;
    }

    public static  function insert_user_token(int $user_id, string $selector, string $hashed_validator, string $expiry): bool
    {

        $insert_token=DB::table('user_tokens')->insert([
            'user_id'=>$user_id,
            'selector'=>$selector,
            'hashed_validator'=>$hashed_validator,
            'expiry'=>$expiry,
        ]);

        return $insert_token;
    }

    public static  function find_user_token_by_selector(string $selector)
    {

        $user=DB::table('user_tokens')->where('selector',$selector)->whereRaw("expiry >= now()")->first();

        return $user;
    }

    public static  function delete_user_token(int $user_id): bool
    {
        $delete=DB::table('user_tokens')->where('user_id',$user_id)->delete();
        return $delete;
    }
    
    public static  function find_user_by_token(string $token)
    {
        $tokens = RememberMe::parse_token($token);

        if (!$tokens) {
            return null;
        }

        $user=DB::table('pemilihs')
        ->join('user_tokens','user_tokens.user_id','=','pemilihs.id')
        ->select('pemilihs.id','pemilihs.username')->where('selector',$tokens[0])->whereRaw("expiry >= now()")->first();

        return $user;
    }
    
    public static  function token_is_valid(string $token)
    {
        [$selector, $validator] =RememberMe::parse_token($token);
        $tokens = RememberMe::find_user_token_by_selector($selector);
        if (!$tokens) {
            return false;
        }

        return password_verify($validator, $tokens->hashed_validator);
    }

    public static  function log_user_in($user): bool
    {
        // prevent session fixation attack
        // if (session_regenerate_id()) {
            // set username & id in the session
            $_SESSION['username'] = $user->username;
            $_SESSION['user_id'] = $user->id;
            return true;
        // }

        // return false;
    }

    public static  function remember_me(int $user_id, int $day = 30)
    {
        [$selector, $validator, $token] = RememberMe::generate_tokens();

        // remove all existing token associated with the user id
        RememberMe::delete_user_token($user_id);

        // set expiration date
        $expired_seconds = time() + 60 * 60 * 24 * $day;

        // insert a token to the database
        $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
        $expiry = date('Y-m-d H:i:s', $expired_seconds);

        if (RememberMe::insert_user_token($user_id, $selector, $hash_validator, $expiry)) {
            setcookie('remember_me', $token, $expired_seconds);
        }
    }

    public static  function logout(): void
    {
        if (RememberMe::is_user_logged_in()) {

            // delete the user token
            RememberMe::delete_user_token($_SESSION['user_id']);

            // delete session
            unset($_SESSION['username'], $_SESSION['user_id`']);

            // remove the remember_me cookie
            if (isset($_COOKIE['remember_me'])) {
                unset($_COOKIE['remember_me']);
                setcookie('remember_user', null, -1);
            }

        }
    }

    public static  function is_user_logged_in(): bool
    {
        // check the session
        if (isset($_SESSION['username'])) {
            return true;
        }

        // check the remember_me in cookie
        $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

        if ($token && RememberMe::token_is_valid($token)) {

            $user = RememberMe::find_user_by_token($token);

            if ($user) {
                return RememberMe::log_user_in($user);
            }
        }
        return false;
    }
    

 }
