package dev.akat.smartmeter.local

interface LocalDataSource {

    fun isLoggedIn(): Boolean

    fun setLoggedIn(isLoggedIn: Boolean)

    fun getAuthId(): Long

    fun setAuthId(authId: Long)
}