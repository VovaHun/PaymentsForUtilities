package dev.akat.smartmeter.ui.accountInfo

import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.model.AccountInfo
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import kotlinx.coroutines.launch
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class AccountInfoViewModel @Inject constructor(private val meterRepository: MeterRepository) :
    ViewModel() {

    private val _accountId = MutableLiveData<Long>()
    private val _isLoading = MutableLiveData<Event<Boolean>>()
    private val _isFetched = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()

    val isLoading: LiveData<Event<Boolean>> get() = _isLoading
    val isFetched: LiveData<Event<Boolean>> get() = _isFetched
    val errorMessage: LiveData<Event<String>> get() = _errorMessage
    val accountInfo: MutableLiveData<AccountInfo> = MutableLiveData()

    fun setAccountId(accountId: Long) {
        _accountId.value = accountId
    }

    fun loadAccountInfo() {
        viewModelScope.launch {
            try {
                _isLoading.postValue(Event(true))
                val response =
                    meterRepository.getAccountInfo(meterRepository.getAuthId(), _accountId.value!!)

                if (response.ok) accountInfo.postValue(response.data)
                else Log.d("AccountInfo", response.error)

                _errorMessage.postValue(Event(response.error))
                _isLoading.postValue(Event(false))
            } catch (e: Exception) {
                _isLoading.postValue(Event(false))
            }
        }
    }

    fun updateDeviceIndications(deviceId: Long, indications: Double) {
        viewModelScope.launch {
            try {
                val response = meterRepository.updateDeviceIndications(deviceId, indications)

                if (response.ok) loadAccountInfo()

                _isFetched.postValue(Event(response.ok))
                _errorMessage.postValue(Event(response.error))
            } catch (e: Exception) {
            }
        }
    }
}