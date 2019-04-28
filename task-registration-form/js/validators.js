(function() {
    //класс для валлилации формы регистрации
    var RegisterForm = function(username, email, name, surname, profileimage, password, repeatpassword) {
        this.errors = [];
        this._username = username;
        this._email = email;
        this._name = name;
        this._surname = surname;
        this._profileimage = profileimage;
        this._password = password;
        this._repeatpassword = repeatpassword;
        
        return this;
    }
    RegisterForm.prototype.add_cl_success = function() {
        var cls = this.classList;
        if( cls.contains('has-error') ) {
            cls.remove('has-error');
        }
        if( !cls.contains('has-success') ) {
            cls.add('has-success');
        }
        return this;
    };
    RegisterForm.prototype.add_cl_error = function() {
        var cls = this.classList;
        if( cls.contains('has-success') ) {
            cls.remove('has-success');
        }
        if( !cls.contains('has-error') ) {
            cls.add('has-error');
        }
        return this;
    };
    RegisterForm.prototype.change_error_mes = function(mes) {
        var ar = this.children;
        for(var i = 0;ar.length >i;i++){
            if( ar[i].classList.contains('box-mes') ) {
                ar[i].innerHTML = mes;
            }
        }
    }
    RegisterForm.prototype.remove_error_mes = function() {
        var ar = this.children;
        for(var i = 0;ar.length >i;i++){
            if( ar[i].classList.contains('box-mes') ) {
                ar[i].innerHTML = '';
            }
        }
    }
    RegisterForm.prototype.delete_from_errors = function(val) {
       if( this.errors.indexOf(val) != -1 ) {
            this.errors.splice(this.errors.indexOf(val) ,1);
            this.errors.sort();        }
    }
//validators START
    RegisterForm.prototype.check_length = function(str, min, max) {
        return str.length >= min && str.length <= max;
    }
    RegisterForm.prototype.username_validator = function(str) {
        var mtchs = str.match(/[^A-Za-z0-9\\-]/giu);
        return mtchs === null;
    }
    RegisterForm.prototype.name_validator = function(str) {
        var mtchs = str.match(/[^A-Za-zА-Яа-я\\-]/giu);
        return mtchs === null;
    }
    RegisterForm.prototype.email_validator = function(str) {
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        return str.search(pattern) === 0;
    }
    RegisterForm.prototype.password_validator = function(str1, str2) {
        return str1 === str2;
    }
    RegisterForm.prototype.image_validator = function(file){ 
        var get_image_extension = function(name) {
            var ar = name.match(/\.[A-z]+$/ig);
            if( ar === null ) {
                return -1;
            }
            return ar[0];
        };
        var allowed_extension = ['.jpg', '.jpeg', '.png'];
        var extension = get_image_extension(file.name);
        if( allowed_extension.indexOf(extension) === -1 ) {
            return -2;
        }
        if( file.size > 2000*2000 ) {
            return -3;
        }
        return true;
    } 
//validator END
    
    RegisterForm.prototype.validateUsername = function() {
        if( !this.check_length(this._username.value, 5, 40 ) ) {
            if( this.errors.indexOf('username') == -1 ) {
                this.errors.push('username');
            }
            this.add_cl_error.call(this._username.parentElement);
            this.change_error_mes.call(this._username.parentElement, 'incorrect length');
            return false;
        }else if( !this.username_validator(this._username.value) ) {
            if( this.errors.indexOf('username') == -1 ) {
                this.errors.push('username');
            }
            this.add_cl_error.call(this._username.parentElement);
            this.change_error_mes.call(this._username.parentElement, 'incorrect characters');
            return false;
        }
        this.delete_from_errors('username');
        this.add_cl_success.call(this._username.parentElement);
        this.remove_error_mes.call(this._username.parentElement);
        return true;
    }
    RegisterForm.prototype.validateEmail = function() {
        if( !this.email_validator(this._email.value) ) {
            if( this.errors.indexOf('email') == -1 ) {
                this.errors.push('email');
            }
            this.add_cl_error.call(this._email.parentElement);
            this.change_error_mes.call(this._email.parentElement, 'incorrect email');
            return false;
        }
        this.delete_from_errors('email');
        this.add_cl_success.call(this._email.parentElement);
        this.remove_error_mes.call(this._email.parentElement);
        return true;
    }
    RegisterForm.prototype.validateName = function() {
        if( !this.check_length(this._name.value, 2, 45 ) ) {
            if( this.errors.indexOf('name') == -1 ) {
                this.errors.push('name');
            }
            this.add_cl_error.call(this._name.parentElement);
            this.change_error_mes.call(this._name.parentElement, 'incorrect length');
            return false;
        }else if( !this.name_validator(this._name.value) ) {
            if( this.errors.indexOf('name') == -1 ) {
                this.errors.push('name');
            }
            this.add_cl_error.call(this._name.parentElement);
            this.change_error_mes.call(this._name.parentElement, 'incorrect characters');
            return false;
        }
        this.delete_from_errors('name');
        this.add_cl_success.call(this._name.parentElement);
        this.remove_error_mes.call(this._name.parentElement);
        return true;
    }
    RegisterForm.prototype.validateSurname = function() {
        if( !this.check_length(this._surname.value, 2, 45 ) ) {
            if( this.errors.indexOf('surname') == -1 ) {
                this.errors.push('surname');
            }
            this.add_cl_error.call(this._surname.parentElement);
            this.change_error_mes.call(this._surname.parentElement, 'incorrect length');
            return false;
        }else if( !this.name_validator(this._surname.value) ) {
            if( this.errors.indexOf('surname') == -1 ) {
                this.errors.push('surname');
            }
            this.add_cl_error.call(this._surname.parentElement);
            this.change_error_mes.call(this._surname.parentElement, 'incorrect characters');
            return false;
        }
        this.delete_from_errors('surname');
        this.add_cl_success.call(this._surname.parentElement);
        this.remove_error_mes.call(this._surname.parentElement);
        return true;
    }
    RegisterForm.prototype.validateProfileimage = function() {
        var $this = this; 
        var add_error = function(mes) {
            if( $this.errors.indexOf('profileimage') == -1 ) {
                $this.errors.push('profileimage');
            }
            $this.add_cl_error.call($this._profileimage.parentElement);
            $this.change_error_mes.call($this._profileimage.parentElement, mes);
        }
        if( this._profileimage.files.length === 0 ) { 
            this.delete_from_errors('profileimage');
            this.add_cl_success.call(this._profileimage.parentElement);
            this.remove_error_mes.call(this._profileimage.parentElement);
            return true;
        }
        if( this._profileimage.files.length > 1 ) {
            add_error('too many images');
            return false;
        }
        switch( this.image_validator(this._profileimage.files[0]) ) {
            case -1:
                add_error('not image');
                return false;
            case -2:
                add_error('incorrect image extension');
                return false;
            case -3:
                add_error('incorrect image size');
                return false;
            case true:
                this.delete_from_errors('profileimage');
                this.add_cl_success.call(this._profileimage.parentElement);
                this.remove_error_mes.call(this._profileimage.parentElement);
                return true;
        }
    }
    RegisterForm.prototype.validatePassword = function() {
        if( !this.check_length(this._password.value, 8, 255 ) ) {
            if( this.errors.indexOf('password') == -1 ) {
                this.errors.push('password');
            }
            this.add_cl_error.call(this._password.parentElement);
            this.change_error_mes.call(this._password.parentElement, 'incorrect length');
            return false;
        }
        this.delete_from_errors('password');
        this.add_cl_success.call(this._password.parentElement);
        this.remove_error_mes.call(this._password.parentElement);
        return true;
    }
    RegisterForm.prototype.validateRepeatpassword = function() {
        if( !this.password_validator(this._password.value, this._repeatpassword.value ) ) {
            if( this.errors.indexOf('repeatpassword') == -1 ) {
                this.errors.push('repeatpassword');
            }
            this.add_cl_error.call(this._repeatpassword.parentElement);
            this.change_error_mes.call(this._repeatpassword.parentElement, 'passwords should match');
            return false;
        }
        this.delete_from_errors('repeatpassword');
        this.add_cl_success.call(this._repeatpassword.parentElement);
        this.remove_error_mes.call(this._repeatpassword.parentElement);
        return true
    }
    RegisterForm.prototype.validate = function() {
        
        this.validateUsername();
        this.validateEmail();
        this.validateName();
        this.validateSurname();
        this.validateProfileimage();
        this.validatePassword();
        this.validateRepeatpassword();
        
        if( this.errors.length !== 0 ) {
            return false;
        }
        return true;
    }
    window.RegisterForm = RegisterForm;
})();  

(function(){
    //класс для валлилации формы логина
    var LoginForm = function(username, password) {
        this.errors = [];
        this._username = username;
        this._password = password;
        
        return this;
    }
    LoginForm.prototype = Object.create(RegisterForm.prototype);
    
    LoginForm.prototype.validate= function() {
        this.validateUsername();
        this.validatePassword();
        
        if( this.errors.length !== 0 ) {
            return false;
        }
        return true;
    }
    
    window.LoginForm = LoginForm;
})();

            
window.onload = function() {
    
    var submit = document.getElementById('regform');
    var login  = document.getElementById('logform');
    
    if( submit !== null ) {
        
        var username = document.getElementById('username');
        var email = document.getElementById('email');
        var name = document.getElementById('name');
        var surname = document.getElementById('surname');
        var profileimage = document.getElementById('profileimage');
        var password = document.getElementById('password');
        var repeatpassword = document.getElementById('repeatpassword');

        var regform = new RegisterForm(username, email, name, surname, profileimage, password, repeatpassword);
        
        //вешаем события для валидации по смене фокуса мышы
        username.addEventListener('blur', function(e) {
            regform.validateUsername();
        });
        email.addEventListener('blur', function(e) {
            regform.validateEmail();
        });
        name.addEventListener('blur', function(e) {
            regform.validateName();
        });
        surname.addEventListener('blur', function(e) {
            regform.validateSurname();
        });
        profileimage.addEventListener('blur', function(e) {
            regform.validateProfileimage();
        });
        password.addEventListener('blur', function(e) {
            regform.validatePassword();
        });
        repeatpassword.addEventListener('blur', function(e) {
            regform.validateRepeatpassword();
        });
        submit.addEventListener('submit', function(e){
            var res = regform.validate();
            if( res ) {
                return true;
            }else {
                e.preventDefault();
                return false;
            }
        });
    }else if( login !== null ){
        var username = document.getElementById('logusername');
        var password = document.getElementById('logpassword');
        
        var logform = new LoginForm(username, password);
        
        username.addEventListener('blur', function(e) {
            logform.validateUsername();
        });
        password.addEventListener('blur', function(e) {
            logform.validatePassword();
        });
        login.addEventListener('submit', function(e){
            var res = logform.validate();
            if( res ) {
                return true;
            }else {
                e.preventDefault();
                return false;
            }
        });
    }
}