<x-app-layout>
    <div class="py-12 flex justify-center">
        <form class="form" method="POST" action="{{ route('register') }}">
            @csrf
            <p class="title">Register </p>
            <p class="message">Signup now and get full access to our dating site. </p>
            
            <div class="flex">
                <label>
                    <input required="true" placeholder="" type="text" class="input" name="first_name" value="{{ old('first_name') }}">
                    <span>First name</span>
                </label>

                <label>
                    <input required="true" placeholder="" type="text" class="input" name="last_name" value="{{ old('last_name') }}">
                    <span>Last name</span>
                </label>
            </div>  
            
            <label>
                <input required="true" placeholder="" type="text" class="input" name="username" value="{{ old('username') }}">
                <span>Username</span>
            </label>
                    
            <label>
                <input required="true" placeholder="" type="email" class="input" name="email" value="{{ old('email') }}">
                <span>Email</span>
            </label>

            <label>
                <input required="true" placeholder="" type="tel" class="input" name="phone_number" value="{{ old('phone_number') }}">
                <span>Phone number</span>
            </label>
                
            <label>
                <input required="true" placeholder="" type="password" class="input" name="password">
                <span>Password</span>
            </label>
            
            <label>
                <input required="true" placeholder="" type="password" class="input" name="password_confirmation">
                <span>Confirm password</span>
            </label>

            <button type="submit" class="submit">Submit</button>
            <p class="signin">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </form>
    </div>
</x-app-layout>