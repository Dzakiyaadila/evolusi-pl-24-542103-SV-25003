export async function fetchPosts() {
  const apiUrl = import.meta.env.VITE_API_URL

  if (!apiUrl) {
    throw new Error('VITE_API_URL belum diatur.')
  }

  const response = await fetch(`${apiUrl.replace(/\/$/, '')}/posts`)

  if (!response.ok) {
    throw new Error('Laravel mengembalikan kesalahan saat mengambil catatan.')
  }

  return response.json()
}