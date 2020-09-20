package dev.akat.smartmeter.ui.accountList

import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.model.AccountSummary
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import kotlinx.coroutines.launch
import javax.inject.Inject

class AccountListViewModel @Inject constructor(private val meterRepository: MeterRepository) :
    ViewModel() {

    private val _isLoading = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()

    val isLoading: LiveData<Event<Boolean>> get() = _isLoading
    val errorMessage: LiveData<Event<String>> get() = _errorMessage
    val accounts: MutableLiveData<List<AccountSummary>> = MutableLiveData()

    init {
        loadAccounts()
    }

    fun logOut() {
        viewModelScope.launch {
            meterRepository.setLoggedIn(false)
        }
    }

    fun loadAccounts() {
        viewModelScope.launch {
            try {
                _isLoading.postValue(Event(true))

                val response = meterRepository.getPersonalAccounts(meterRepository.getAuthId())
                if (response.ok) accounts.postValue(response.data)
                else Log.d("AccountList", response.error)

                _isLoading.postValue(Event(false))
            } catch (e: Exception) {
                _isLoading.postValue(Event(false))
            }
        }
    }
}