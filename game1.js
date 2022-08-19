const c = document.getElementById('pane').getContext('2d')
const w = c.canvas.width
const h = c.canvas.height
let score = 0
let gameOver = false

function Enemy () {
  // Initial size
  this.isize = 20
  // Initial maximum positional velocity
  this.maxDxy = 4
  // Initial maximum angular velocity
  this.maxDa = 8

  this.init = function () {
    // Reset the size
    this.size = this.isize
    const s = this.size
    // Initial position is a random spot along
    // the edge
    if (Math.random() < 0.5) {
      if (Math.random() < 0.5) {
        this.x = s / 2
      } else {
        this.x = w - s / 2
      }
      this.y = Math.random() * (h - s) + s / 2
    } else {
      this.x = Math.random() * (w - s) + s / 2
      if (Math.random() < 0.5) {
        this.y = s / 2
      } else {
        this.y = h - s / 2
      }
    }
    // Initial angle is random
    this.angle = Math.random() * 360
    // Velocities are random
    const maxDxy = this.maxDxy
    this.dx = maxDxy * (Math.random() - 0.5)
    this.dy = maxDxy * (Math.random() - 0.5)
    this.da = this.maxDa * (Math.random() - 0.5)
    // Each time init() is called, increase the
    // maximum position velocity and the initial
    // size
    this.maxDxy *= 1.1
    this.isize *= 1.05
  }
  // Call init immediately in the constructor
  this.init()

  this.move = function () {
    // Are we dead?
    if (this.size <= 0) {
      // We are dead, but we shall be reborn!
      // Respawn at the original size
      // at a random location along the edge
      // and a little faster than before.
      this.init()
    }
    // Are we being shot?
    if (isMouseDown) {
      const diffX = mouse.x - this.x
      const diffY = mouse.y - this.y
      const dist = Math.sqrt(diffX * diffX +
        diffY * diffY)

      if (dist < this.size / 2 || dist < 10) {
        // We've been hit! Decrease our size
        // and increase the score.
        this.size -= 2
        score += 110
        if (this.size <= 0) { return }
      }
    }
    // Move, which means change our
    // position and angle by our velocity
    this.angle += this.da
    this.x += this.dx
    this.y += this.dy
    // Have we hit an edge? If so, bounce off.
    const s = this.size
    if (this.x < s / 2 ||
      this.x + s / 2 > w) {
      this.dx = -this.dx
      this.da = -this.da
    }
    if (this.y < s / 2 ||
      this.y + s / 2 > h) {
      this.dy = -this.dy
      this.da = -this.da
    }
  }

  this.draw = function () {
    const s = this.size
    const x = this.x
    const y = this.y
    // We don't draw dead guys
    if (s <= 0) { return }
    // Draw by first moving to the center of
    // the object, rotating around that center,
    // then drawing around the center
    c.save()
    c.translate(x, y)
    c.rotate(this.angle * Math.PI / 180)
    c.strokeRect(-s / 2, -s / 2, s, s)
    c.restore()
  }
}

function Base () {
  this.x = w / 2
  this.y = h / 2
  this.size = 20
  this.angle = 0
  this.da = 3

  this.isHitBy = function (obj) {
    // Checks if obj overlaps with this.
    // Assumes obj has x, y, and size.
    const x = this.x - obj.x
    const y = this.y - obj.y
    const d = Math.sqrt(x * x + y * y)
    if (d < this.size / 2 + obj.size / 2) {
      return true
    }
    return false
  }

  this.move = function () {
    // Bases only rotate, no movement
    this.angle += this.da
    // Shooting the laser costs points. That
    // keeps the player from shooting all the
    // time even when they'd miss.
    if (isMouseDown) {
      score -= 10
    }
  }

  this.draw = function () {
    // Draw the base as three squares, the
    // second two rotated 30 degrees from the
    // previous
    c.save()
    const s = this.size
    const x = this.x
    const y = this.y
    c.translate(x, y)
    c.rotate(this.angle * Math.PI / 180)
    c.strokeRect(-s / 2, -s / 2, s, s)
    c.rotate(30 * Math.PI / 180)
    c.strokeRect(-s / 2, -s / 2, s, s)
    c.rotate(30 * Math.PI / 180)
    c.strokeRect(-s / 2, -s / 2, s, s)
    c.restore()
    // Are we shooting the laser?
    if (isMouseDown) {
      // If so, draw the laser
      c.beginPath()
      c.moveTo(x, y)
      c.lineTo(mouse.x, mouse.y)
      c.stroke()
    }
  }
}

// Keep track of whether the mouse is pressed
var isMouseDown = false
c.canvas.onmousedown =
  function (evt) { isMouseDown = true }
c.canvas.onmouseup =
  function (evt) { isMouseDown = false }

// Keep track of where the mouse is
var mouse = { x: 0, y: 0 }
c.canvas.onmousemove =
  function (evt) {
    mouse.x = evt.clientX
    mouse.y = evt.clientY
  }

// Create the enemies and the base
const enemies = []
const numEnemies = 5
for (let i = 0; i < numEnemies; i++) {
  const e = new Enemy()
  enemies.push(e)
}
const base = new Base()

// This is the main animation loop. Move
// and redraw everything many times every
// second.
let cmTID
const timeStep = 50 // In milliseconds
function updateAll () {
  // Move everything
  for (var i = 0; i < numEnemies; i++) {
    const enemy = enemies[i]
    enemy.move()
    if (base.isHitBy(enemy)) {
      gameOver = true
    }
  }
  base.move()
  // Erase everything
  c.clearRect(0, 0, w, h)
  // Redraw everything
  for (i = 0; i < numEnemies; i++) {
    enemies[i].draw()
  }
  base.draw()
  // Show the score on the screen
  c.fillText('Score: ' + score, w * 0.8, 20)
  if (gameOver) {
    // If the game is over, display game over
    // text
    c.save()
    c.font = '48pt sans-serif'
    c.textAlign = 'center'
    c.fillStyle = 'red'
    c.textBaseline = 'middle'
    c.fillText('GAME OVER', w / 2, h / 2)
    c.restore()
  }
  // Do it all again in a little while
  clearTimeout(cmTID)
  if (!gameOver) {
    // Only animate if the game isn't over
    cmTID = setTimeout(updateAll, timeStep)
  }
}
updateAll()
