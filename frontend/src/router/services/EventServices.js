import axios from 'axios'

// Axios instanca za API klice
const apiClients = axios.create({
  baseURL: 'http://localhost:8000/api/',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
})

// 🔐 Nastavi Authorization header, če je token v localStorage
export function loadAuthToken() {
  const savedToken = localStorage.getItem('auth_token')
  if (savedToken) {
    apiClients.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`
  }
}

// 🔧 Pokliči ob zagonu aplikacije
loadAuthToken()

// 📦 API metode
const EventServices = {
  // Prijava
  login(email, password) {
    return apiClients.post('/login', { email, password })
      .then(response => {
        const token = response.data.token
        localStorage.setItem('auth_token', token)
        loadAuthToken()
        return response
      })
  },

  // Odjava
  logout() {
    return apiClients.post('/logout').then(() => {
      localStorage.removeItem('auth_token')
      delete apiClients.defaults.headers.common['Authorization']
    })
  },

  // Registracija
  getRegister() {
    return apiClients.get('/register')
  },

  register(name, email, password) {
    return apiClients.post('/register', {
      name,
      email,
      password
    })
  },

  // Stranke
  getStranke() {
    return apiClients.get('/stranke')
  },

  getStranka(id) {
    return apiClients.get(`stranke/${id}`)
  },

  addStranka(data) {
    return apiClients.post('stranke/dodaj', data)
  },

  updateStranka(id, data) {
    return apiClients.put(`stranke/${id}`, data)
  },

  deleteStranka(id) {
    return apiClients.delete(`stranke/${id}`)
  },

  // Tveganja
  getTveganja() {
    return apiClients.get('tveganja')
  },

  getTveganje(id) {
    return apiClients.get(`tveganja/${id}`)
  },

  createTveganje(data) {
    return apiClients.post('tveganja', data)
  },

  updateTveganje(id, payload) {
    return apiClients.put(`tveganja/${id}`, payload)
  },

  deleteTveganje(id) {
    return apiClients.delete(`tveganja/${id}`)
  },

  // Uporabnik
  getCurrentUser() {
    return apiClients.get('user')
  },

  getMojeStranke() {
    return apiClients.get('/moje-stranke')
  },

  getTveganjaZaStranko(strankaId) {
    console.log('Nalagam tveganja za stranko:', strankaId.value)
    return apiClients.get(`/stranke/${strankaId}/tveganja`)
  },

  // ✅ AI zahtevek z vsemi podatki
  posljiAiZahtevek({ id, ime, navodila }) {
    return apiClients.post('/ai/predlogi', {
      tveganje_id: id,
      tveganje: ime,
      navodila
    })
  },
  // Heatmap podatki
  getTopTveganja(limit = 10) {
    return apiClients.get(`risks/top?limit=${limit}`)
  },
  runScraper() {
  return apiClients.post("/scrape-run")
    .then(res => {
      console.log("Scraper output:", res.data.output)
      alert("✅ Scraper uspešno zagnan!")
    })
    .catch(err => {
      console.error("Napaka pri scraperju:", err)
      alert("❌ Scraper ni uspel")
    })
  },
  



}

export default EventServices
export { apiClients }