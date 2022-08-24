import { jest, describe, it, expect, beforeEach, afterAll } from '@jest/globals'
import { Game, Booster, Enemy, restartLevel, endgameDisplayLayout } from './gameNew.js'
import { main } from './index.js'

document.body.innerHTML = `<canvas id='pane'></canvas><input id="inputtext">
<button id = "continuebtn"></button>
<button id = "restartbtn"></button>
<button id = "homebtn"></button>`

describe("game", () => {
    // canvas setup
    const c = document.getElementById('pane').getContext('2d')
    c.canvas.width = 100
    c.canvas.height = 100
    let w = c.canvas.width
    let h = c.canvas.height

    // variables for booster/enemy
    const boosterTypes = ['slowmo', 'freezer', 'freepts']
    const wordID = 20
    const foreignWord = 'hsk'
    const romanWord = 'pin'
    const engWord = 'eng'
    const gameMode = ['Regular', 'Beat The Clock', 'Race To Finish', 'Endurance', 'Practice']
    const initialSpeed = 3

    const booster = new Booster(boosterTypes[0], c,
        w, h, wordID, foreignWord, romanWord, engWord)
    describe("booster", () => {
        it("should initialize values with init() function", () => {
            expect(booster.wordID).toBe(20)
        })

        it("booster should call move() and move location", () => {
            booster.move()
            expect(booster.y).toBe(booster.isize / 2 + booster.maxDy)
        })

        it("booster death should be registered", () => {
            booster.passfail = 'pass'            
            booster.draw()
            expect(booster.size).toBe(booster.isize - 1)
        })

        it("should be green booster", () => {
            const slowMoBooster = new Booster(boosterTypes[0], c,
                w, h, wordID, foreignWord, romanWord, engWord)
            expect(slowMoBooster.g).toBe(255)
        })

        it("should be blue booster", () => {
            const freezeBooster = new Booster(boosterTypes[1], c,
                w, h, wordID, foreignWord, romanWord, engWord)
            expect(freezeBooster.b).toBe(255)
        })

        it("should be red booster", () => {
            const frenzyBooster = new Booster(boosterTypes[2], c,
                w, h, wordID, foreignWord, romanWord, engWord)
            expect(frenzyBooster.r).toBe(255)
        })
    })

    const enemy = new Enemy(gameMode[0], initialSpeed, c, w, wordID, foreignWord, romanWord, engWord)
    describe("enemy", () => {
        it("should initialize values with init() function", () => {
            expect(enemy.wordID).toBe(20)
        })

        it("should call move() and move location", () => {
            enemy.move()
            expect(enemy.y).toBe(enemy.isize / 2 + enemy.maxDy)
        })

        it("enemy death should be registered", () => {
            enemy.passfail = 'pass'            
            enemy.draw()
            expect(enemy.size).toBe(enemy.isize - 1)
        })
    })

    // describe("game", () => {
    //     it("should initialize game", () => {
    //         const newGame = new game(0,25)
    //         expect(newGame.updateAll).toBeCalled()
    //     })
    // })
})