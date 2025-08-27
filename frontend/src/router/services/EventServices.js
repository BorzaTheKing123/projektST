import axios from 'axios'

// To bo omogočilo, da Axios pošilja piškotke s vsako zahtevo
axios.defaults.withCredentials = true;

// To bo omogočilo, da Axios samodejno pretvori piškotek v glavo
//axios.defaults.withXSRFToken = true; 

const apiClients = axios.create({
    baseURL: 'http://localhost:8000/api/',
    withCredentials: true,
    //withXSRFToken: true,
    headers:{
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
})

export default { 
    getRegister(){
        return apiClients.get('/register')
    },
    register(name, email, phone){
        return apiClients.post('/register',{
            name,
            email,
            phone
        })

    },
    login(email , password ) {
        return apiClients.post('/login', {
        email,
        password,
        })
    },
    getLogin(){
        return apiClients.get('/sanctum/csrf-cookie')
    },
    getStranka(id, stranke){
        return apiClients.get('/stranke/'+id,{
            params: {stranke}
        })
    },
    getStranke(){
        return apiClients.get('/stranke',{
        })
    },
   dodajStranke(data){
  return apiClients.post('/stranke/dodaj', data)
}

}