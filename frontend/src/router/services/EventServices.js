import axios from 'axios'

const apiClients = axios.create({
    baseURL: '',
    withCredentials: false,
    headers:{
        Accept: 'application/json',
        'Content-Type': 'application/json',
    }
})

export default {


    
}