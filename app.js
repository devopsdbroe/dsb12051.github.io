(function() {
	
	// Initialize Firebase
	const config = {
		apiKey: "AIzaSyCZssIoxpw_n8if6d-UOwH_X3dmQMIZ6lA",
		authDomain: "jd-weather-station.firebaseapp.com",
		databaseURL: "https://jd-weather-station.firebaseio.com",
		storageBucket: "jd-weather-station.appspot.com",
	};
	firebase.initializeApp(config);
	
	// Get Elements
	const txtEmail = document.getElementById('txtEmail');
	const txtPassword = document.getElementById('txtPassword');
	const btnLogin = document.getElementById('btnLogin');
	const btnSignUp = document.getElementById('btnSignUp');
	const btnLogout = document.getElementById('btnLogout');
	
	// Add login event
	btnLogin.addEventListener('click', e => {
		// Get email and pass
		const email = txtEmail.value;
		const pass = txtPassword.value;
		const auth = firebase.auth();
		// Sign in
		const promise = auth.signInWithEmailAndPassword(email, pass);
		promise
			.catch(e => window.alert("Username or Password is incorrect"));
		promise
			.then(e => window.location.href = "index.php");
	});
		
	// Add signup event
	btnSignUp.addEventListener('click', e => {
		// Get email and pass
		const email = txtEmail.value;
		const pass = txtPassword.value;
		const auth = firebase.auth();
		// Sign up
		const promise = auth.createUserWithEmailAndPassword(email, pass);
		promise
			.catch(e => window.alert(e.message));
		promise
			.then(e => window.alert("Your account was successfully registered"));
	});
	
	btnLogout.addEventListener('click', e=> {
		firebase.auth().signOut();
	});
	
	// Add a realtime listner
	firebase.auth().onAuthStateChanged(firebaseUser => {
		if(firebaseUser) {
			console.log(firebaseUser);
			btnLogout.classList.remove('hide');
		} else {
			console.log('not logged in');
			btnLogout.classList.add('hide');
		}
	});
	
}());