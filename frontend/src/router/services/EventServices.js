import axios from 'axios'

const apiClients = axios.create({
    baseURL: 'http://projektst.test/',
    withCredentials: false,
    headers:{
        Accept: 'application/json',
        'Content-Type': 'application/json',
    }
})

export default {
    getRegister(){
        return apiClients.get('/register')
    },



}