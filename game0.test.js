import { jest, describe, it, expect, beforeEach, afterAll } from '@jest/globals'
import { Game, Booster, Enemy, spawnEnemyCheck, isGameOver, restartLevel, endgameDisplayLayout } from './gameNew.js'
import { main } from './index.js'

document.body.innerHTML = `<canvas id='pane'></canvas><input id="inputtext">
<button id = "continuebtn"></button>
<button id = "restartbtn"></button>
<button id = "homebtn"></button>
<form id = "customGameForm"></form>`

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
            expect(booster.size).toBe(booster.isize - 1 / 3)
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
            expect(enemy.size).toBe(enemy.isize - 1 / 3)
        })
    })

    describe("enemy spawn checker", () => {
        const enemyTypes = ['Regular', 'Practice', 'Race To Finish', '']
        for (const type of enemyTypes) {
            it(`should return true for ${type} enemy`, () => {
                const enemyCheck = spawnEnemyCheck(type, 40, 30, 30, 0, 0)
                expect(enemyCheck).toBe(true)
            })

            it(`should return false for ${type} enemy`, () => {
                const enemyCheck = spawnEnemyCheck(type, 30, 40, 30, 1, 1)
                expect(enemyCheck).toBe(false)
            })
        }
    })

    describe("gameover checks", () => {
        const gameModesWithFail = ['Regular', 'Beat The Clock', 'Endurance', 'Practice', 'Race To Finish']
        for (const mode of gameModesWithFail) {
            it(`should change state to finish and result to fail for ${mode} game mode`, () => {
                const gameOver = isGameOver(mode, 0)
                expect(gameOver.gameState).toBe('finish')
                expect(gameOver.gameResult).toBe('fail')
            })

            it(`should return false for ${mode} game mode when condition not met`, () => {
                const gameOver = isGameOver(mode, 1)
                expect(gameOver).toBe(false)
            })
        }

        const gameModesWithPass = ['Regular', 'Beat The Clock', 'Practice', 'Race To Finish']
        for (const mode of gameModesWithPass) {
            it(`should change state to finish and result to pass for ${mode} game mode`, () => {
                const gameOver = isGameOver(mode, 10, 0, 40, 0, 40)
                expect(gameOver.gameState).toBe('finish')
                expect(gameOver.gameResult).toBe('pass')
            })
        }
    })
})