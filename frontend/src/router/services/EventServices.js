import axios from 'axios'

// Axios bo pošiljal piškotke z vsako zahtevo
axios.defaults.withCredentials = true

const apiClients = axios.create({
    baseURL: 'http://localhost:8000/api/',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
})

// Funkcija za nastavitev Authorization glave po prijavi
function loadAuthToken() {
    const savedToken = localStorage.getItem('auth_token')
    if (savedToken) {
     apiClients.defaults.headers.common['Authorization'] = `Bearer ${token}`
}}

loadAuthToken()

export default {
    getRegister() {
        return apiClients.get('/register')
    },

    register(name, email, phone) {
        return apiClients.post('/register', {
            name,
            email,
            phone
        })
    },

    // 🔐 Prijava z avtomatskim CSRF klicem
    async login(email, password) {
        // Najprej pridobi CSRF piškotek
       // await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
        //    withCredentials: true
       // })
   


        // Nato pošlji prijavo
        const response = await apiClients.post('/login', {
            email,
            password
        })

        // Shrani token in nastavi glavo
        const token = response.data.token
    localStorage.setItem('auth_token', token)
    loadAuthToken()

    return response

    },

    getStranka(id, stranke) {
        return apiClients.get(`/stranke/${id}`, {
            params: { stranke }
        })
    },

    getStranke() {
        return apiClients.get('/stranke')
    },

    dodajStranke(data) {
        return apiClients.post('/stranke/dodaj', data)
    },

    logout() {
        return apiClients.post('/logout').then(() => {
            localStorage.removeItem('auth_token')
            delete apiClients.defaults.headers.common['Authorization']
        })
    }
}
