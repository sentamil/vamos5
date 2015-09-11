<?php

class RemindersController extends Controller {

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind() {
        return View::make('password.remind');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind() {

        $data = Input::all();
        $userid = Input::get('userid');
        //$email=Input::get('email');
        $email = User::where('username', '=', $userid) -> pluck('email');
        log::info(' email = ' . $email);

        $response = Password::remind(array('email' => $email), function($message) {
            $message -> subject('Password Reminder');
        });

        switch ($response) {
            case Password::INVALID_USER :
                return Redirect::back() -> with('error', Lang::get($response));

            case Password::REMINDER_SENT :
                return Redirect::back() -> with('flash', 'Please check your mail and click the link');
        }
    }

    public function reset($token) {

        return View::make('password.reset') -> with('token', $token);
    }

    public function update() {
        //$credentials = array('email' => Input::get('email'));
        $userid = Input::get('userid');

        $email = User::where('username', '=', $userid) -> pluck('email');

        $credentials = Input::only('password', 'password_confirmation', 'token');
        $credentials = array_add($credentials, 'email', $email);
        log::info(" update...");
        $response = Password::reset($credentials, function($user, $password) {

            $userid = Input::get('userid');
            $count = User::where('username', '=', $userid) -> update(array('password' => Hash::make($password)));

            log::info(" saved " . $count);

            return Redirect::to('login') -> with('message', 'Your password has been reset');
        });
        switch ($response) {
            case Password::INVALID_PASSWORD :
                log::info('invalid password');
            case Password::INVALID_TOKEN :
                log::info('invalid token');
            case Password::INVALID_USER :
                log::info(" invalid.....");
                return Redirect::back() -> withErrors('Invalid User or Password');

            case Password::PASSWORD_RESET :
                return Redirect::to('login') -> with('flash', 'Your password has been reset');
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null) {
        if (is_null($token))
            App::abort(404);

        return View::make('password.reset') -> with('token', $token);
    }

}
