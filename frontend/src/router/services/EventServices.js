import axios from 'axios'

const apiClients = axios.create({
    baseURL: 'http://127.0.0.1:8000/',
    withCredentials: true,
    headers:{
        Accept: 'application/json',
        'Content-Type': 'application/json',
    }
})

export default {
    getRegister(){
        return apiClients.get('/register')
    },
    login(email , password ) {
        return apiClients.post('/login', {
        email,
        password
        })
    },
    getLogin(){
        return apiClients.get('/login')
    },
    getStranke(){
        return apiClients.get('/stranke')
    },
}