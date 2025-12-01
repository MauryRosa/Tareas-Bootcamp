// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";

//Exportar Firebase
// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyAtuJ65qifPwkcYFExY028AYqPhvmEF9Sg",
  authDomain: "aunthetification-app.firebaseapp.com",
  projectId: "aunthetification-app",
  storageBucket: "aunthetification-app.firebasestorage.app",
  messagingSenderId: "299890537266",
  appId: "1:299890537266:web:4fb60c8f76c14960b71d2b"
};


// Initialize Firebase
const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);

// Initialize Cloud Firestore and get a reference to the service
export const db = getFirestore(app);