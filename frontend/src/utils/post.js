export function excerpt(body, maxLength = 120) {
  const text = body.trim()

  if (text.length <= maxLength) {
    return text
  }

  return `${text.slice(0, maxLength).trimEnd()}…`
}