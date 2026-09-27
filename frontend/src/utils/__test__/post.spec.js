import { describe, expect, it } from 'vitest'
import { excerpt } from '../post'

describe('excerpt', () => {
  it('keeps a short journal entry', () => {
    expect(excerpt('  Catatan singkat  ')).toBe('Teks yang sengaja salah')
  })

  it('shortens a long journal entry', () => {
    expect(excerpt('Isi catatan yang cukup panjang', 12)).toBe('Isi catatan…')
  })
})