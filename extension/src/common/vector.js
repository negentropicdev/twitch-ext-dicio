export default {
  length(vec) {
    return Math.sqrt(vec.x * vec.x + vec.y * vec.y);
  },
  
  normalize(vec) {
    var h = length(vec);
    return {
      x: vec.x / h,
      y: vec.y / h
    }
  },
  
  scale(vec, s) {
    return {
      x: vec.x * s,
      y: vec.y * s
    }
  },

  scale2(a, b) {
    return {
      x: a.x * b.x,
      y: a.y * b.y
    }
  },
  
  add(a, b) {
    return {
      x: a.x + b.x,
      y: a.y + b.y
    }
  },
  
  sub(a, b) {
    return {
      x: a.x - b.x,
      y: a.y - b.y
    }
  }
}